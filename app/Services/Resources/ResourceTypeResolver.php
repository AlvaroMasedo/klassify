<?php

namespace App\Services\Resources;

class ResourceTypeResolver
{
    /**
     * Mapeo de extensiones de fitxer a tipus de recurs.
     */
    private const EXTENSION_TYPE_MAP = [
        'pdf' => 'document',
        'doc' => 'document',
        'docx' => 'document',
        'ppt' => 'document',
        'pptx' => 'document',
        'mp4' => 'video',
        'mp3' => 'audio',
        'jpg' => 'image',
        'jpeg' => 'image',
        'png' => 'image',
    ];

    /**
     * Tipus de recurs permesos.
     */
    private const ALLOWED_TYPES = ['document', 'video', 'audio', 'image', 'exam'];

    /**
     * Resoldre el tipus de recurs segons l'extensió del fitxer.
     *
     * @param string $extension L'extensió del fitxer (sense punt)
     * @return string|null El tipus de recurs o null si no és vàlid
     */
    public function resolve(string $extension): ?string
    {
        $extension = strtolower($extension);

        return self::EXTENSION_TYPE_MAP[$extension] ?? null;
    }

    /**
     * Obtenir totes les extensions permitides.
     *
     * @return array<string> Llista de extensions vàlides
     */
    public function getAllowedExtensions(): array
    {
        return array_keys(self::EXTENSION_TYPE_MAP);
    }

    /**
     * Obtenir les extensions permeses per a un tipus de recurs.
     *
     * @param string $type El tipus de recurs (document, video, audio, image)
     * @return array<string> Llista d'extensions per a aquest tipus
     */
    public function getExtensionsByType(string $type): array
    {
        $type = strtolower($type);

        return array_keys(array_filter(
            self::EXTENSION_TYPE_MAP,
            fn(string $resourceType) => $resourceType === $type
        ));
    }

    /**
     * Verificar si una extensió és vàlida.
     *
     * @param string $extension L'extensió del fitxer
     * @return bool
     */
    public function isValid(string $extension): bool
    {
        return in_array(strtolower($extension), $this->getAllowedExtensions(), true);
    }

    /**
     * Obtenir els tipus de recurs permesos.
     *
     * @return array<string> Llista de tipus vàlids
     */
    public function getAllowedTypes(): array
    {
        return self::ALLOWED_TYPES;
    }

    /**
     * Generar una llista de formats acceptats per a validacions de formulari.
     * Retorna una cadena d'extensions separades per comes, útil per a l'atribut accept de <input type="file">.
     *
     * @return string Format: ".pdf,.doc,.docx,.ppt,.pptx,.mp4,.mp3,.jpg,.jpeg,.png"
     */
    public function getAcceptFormats(): string
    {
        return '.' . implode(',.', $this->getAllowedExtensions());
    }

    /**
     * Generar una cadena de formats MIME para validacions.
     * Retorna les extensió separades per comes, útil per a regles de validació.
     *
     * @return string Format: "pdf,doc,docx,ppt,pptx,mp4,mp3,jpg,jpeg,png"
     */
    public function getValidationFormats(): string
    {
        return implode(',', $this->getAllowedExtensions());
    }

    /**
     * Obtenir les extensions vàlides per a regles de validació Laravel (estàtiques).
     * Útil per a FormRequests que no poden inyectar servicis.
     *
     * @return string Format: "pdf,doc,docx,ppt,pptx,mp4,mp3,jpg,jpeg,png"
     */
    public static function getMimeValidationRule(): string
    {
        return 'pdf,doc,docx,ppt,pptx,mp4,mp3,jpg,jpeg,png';
    }
}
