@props([
    'user' => null,
    'alt' => 'Avatar',
])

@php
    $defaultSrc = asset('assets/img/default-profile-img.png');
    $rawSrc = trim((string) ($user?->foto_perfil_url ?? ''));

    if ($rawSrc === '') {
        $src = $defaultSrc;
    } elseif (
        str_starts_with($rawSrc, 'http://')
        || str_starts_with($rawSrc, 'https://')
        || str_starts_with($rawSrc, '//')
    ) {
        $src = $rawSrc;
    } else {
        $s3Path = ltrim((string) parse_url($rawSrc, PHP_URL_PATH), '/');

        if ($s3Path === '') {
            $src = $defaultSrc;
        } else {
            try {
                /** @var \Illuminate\Filesystem\AwsS3V3Adapter $s3Disk */
                $s3Disk = Storage::disk('s3');
                $src = $s3Disk->temporaryUrl($s3Path, now()->addHour());
            } catch (\Throwable) {
                $s3BaseUrl = trim((string) config('filesystems.disks.s3.url'));

                if ($s3BaseUrl !== '') {
                    $src = rtrim($s3BaseUrl, '/') . '/' . $s3Path;
                } else {
                    $s3Endpoint = trim((string) config('filesystems.disks.s3.endpoint'));
                    $s3Bucket = trim((string) config('filesystems.disks.s3.bucket'));
                    $s3Region = trim((string) config('filesystems.disks.s3.region'));

                    if ($s3Endpoint !== '') {
                        $src = rtrim($s3Endpoint, '/') . '/' . $s3Bucket . '/' . $s3Path;
                    } elseif ($s3Bucket !== '' && $s3Region !== '') {
                        $src = 'https://' . $s3Bucket . '.s3.' . $s3Region . '.amazonaws.com/' . $s3Path;
                    } else {
                        $src = $defaultSrc;
                    }
                }
            }
        }
    }
@endphp

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="lazy"
    decoding="async"
    onerror="this.onerror=null;this.src='{{ $defaultSrc }}';"
    {{ $attributes->merge(['class' => 'user-avatar']) }}>