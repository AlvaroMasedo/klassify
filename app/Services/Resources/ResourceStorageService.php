<?php

namespace App\Services\Resources;

use App\Models\Resource;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResourceStorageService
{
    private const S3_DISK = 's3';

    /**
     * Obtenir l'adaptador del sistema de fitxers S3.
     *
     * @return FilesystemAdapter
     */
    private function getS3Disk(): FilesystemAdapter
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk(self::S3_DISK);
        return $disk;
    }

    /**
     * Pujar un fitxer a S3 i retornar la seva URL i metadades.
     *
     * @param UploadedFile $file
     * @param int $userId
     * @return array{url: string, name: string, size: int, mime: string}
     */
    public function uploadFile(UploadedFile $file, int $userId): array
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $folder = 'resources/' . $userId;
        $fileName = Str::uuid() . '.' . $extension;

        $path = $this->getS3Disk()->putFileAs($folder, $file, $fileName);

        if ($path === false) {
            throw new \Exception('No s\'ha pogut pujar el fitxer a S3.');
        }

        $url = $this->getS3Disk()->url($path);

        return [
            'url' => $url,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
        ];
    }

    /**
     * Reemplaçar un fitxer de recurs existent amb un de nou.
     * Puja el fitxer nou primer i, si té éxit, elimina el fitxer antic.
     * Si la subida nova falla, el fitxer antic es conserva intacte.
     * Si l'eliminació del fitxer antic falla però la subida ja va funcionar,
     * es registra l'error però es retorna la nova URL correctament.
     *
     * @param UploadedFile $newFile
     * @param Resource $resource
     * @return array{url: string, name: string, size: int, mime: string}
     */
    public function replaceFile(UploadedFile $newFile, Resource $resource): array
    {
        // Primer: Puja el fitxer nou
        $newFileData = $this->uploadFile($newFile, $resource->user_id);

        // Si hem arribat aquí, la subida del fitxer nou va correctament.
        // Ara intentem eliminar el fitxer antic.
        $oldS3Path = $this->normalizeS3FileUrl((string) ($resource->file_url ?? ''));

        if ($oldS3Path !== null) {
            try {
                $this->getS3Disk()->delete($oldS3Path);
            } catch (\Throwable $e) {
                // Si falla el borrado del fitxer antic, registra l'error però continua.
                // El fitxer nou ja està salvat correctament.
                Log::warning('Failed to delete old resource file from S3', [
                    'resource_id' => $resource->id,
                    's3_path' => $oldS3Path,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Retorna les dades del fitxer nou
        return $newFileData;
    }

    /**
     * Eliminar un fitxer de recurs d'S3.
     * Falla silenciosament si el fitxer no existeix, l'URL està buida,
     * o es produeixen errors d'eliminació.
     *
     * @param Resource $resource
     * @return void
     */
    public function deleteFile(Resource $resource): void
    {
        $s3Path = $this->normalizeS3FileUrl((string) ($resource->file_url ?? ''));

        if ($s3Path === null) {
            return;
        }

        try {
            $this->getS3Disk()->delete($s3Path);
        } catch (\Throwable) {
            // Ignora silenciosament els errors d'emmagatzematge per evitar bloquejar els fluxos d'eliminació/actualització.
        }
    }

    /**
     * Extreure el camí S3 d'una URL pública de S3.
     * Retorna el camí relatiu al bucket (sense el nom del bucket).
     * Retorna null si l'URL està buida o no és vàlida.
     *
     * Compatible amb URLs antigues i noves de S3.
     *
     * @param string $fileUrl
     * @return string|null
     */
    private function normalizeS3FileUrl(string $fileUrl): ?string
    {
        $fileUrl = (string) ($fileUrl ?? '');

        if ($fileUrl === '') {
            return null;
        }

        return $this->extractS3Path($fileUrl);
    }

    /**
     * Extreure el camé S3 d'una URL pública de S3.
     * Retorna el camí relatiu al bucket (sense el nom del bucket).
     *
     * @param string $fileUrl
     * @return string|null
     */
    public function extractS3Path(string $fileUrl): ?string
    {
        $parsed = parse_url($fileUrl, PHP_URL_PATH);

        if (!is_string($parsed) || $parsed === '') {
            return null;
        }

        $normalizedPath = ltrim($parsed, '/');
        $bucket = (string) config('filesystems.disks.s3.bucket');

        // Elimina el prefix del nom del bucket si està present
        if ($bucket !== '' && str_starts_with($normalizedPath, $bucket . '/')) {
            $normalizedPath = substr($normalizedPath, strlen($bucket) + 1);
        }

        return $normalizedPath ?: null;
    }

    /**
     * Servir un fitxer de recurs inline des d'S3 (per a previsualització).
     * Retorna una Resposta de Symfony amb disposició inline.
     *
     * Compatible amb PDF, imatges, àudio, vídeo i documents.
     * El tipus MIME és estabelert automàticament per Storage/S3.
     *
     * @param Resource $resource
     * @return Response
     */
    public function serveFileInline(Resource $resource): Response
    {
        $s3Path = $this->normalizeS3FileUrl((string) ($resource->file_url ?? ''));

        if ($s3Path === null) {
            abort(404);
        }

        return $this->getS3Disk()->response($s3Path, $resource->file_name ?: null, [
            'Content-Disposition' => 'inline',
        ]);
    }

    /**
     * Generar una URL temporal per a un fitxer de recurs (si s'utilitzen URL signades).
     * Retorna una URL que caduca després dels minuts especificats.
     * Torna a la URL permanent si les URL temporals no estan disponibles.
     *
     * @param Resource $resource
     * @param int $minutesValid
     * @return string
     */
    public function getTemporaryUrl(Resource $resource, int $minutesValid = 60): string
    {
        $s3Path = $this->normalizeS3FileUrl((string) ($resource->file_url ?? ''));

        if ($s3Path === null) {
            return '';
        }

        try {
            return $this->getS3Disk()->temporaryUrl(
                $s3Path,
                now()->addMinutes($minutesValid)
            );
        } catch (\Throwable) {
            // Si les URL temporals no estan suportades, retorna la URL permanent
            return (string) ($resource->file_url ?? '');
        }
    }

    /**
     * Obtenir la URL de visualització d'un recurs al feed.
     * Retorna la ruta de previsualització local per evitar exposar les URL d'S3 directament.
     * Retorna null si l'URL del fitxer és buida.
     *
     * @param Resource $resource
     * @return string|null
     */
    public function getDisplayUrl(Resource $resource): ?string
    {
        $s3Path = $this->normalizeS3FileUrl((string) ($resource->file_url ?? ''));

        if ($s3Path === null) {
            return null;
        }

        return route('resources.preview', ['resource' => $resource->id]);
    }
}
