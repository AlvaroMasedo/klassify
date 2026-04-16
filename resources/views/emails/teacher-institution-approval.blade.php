<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validación de profesor</title>
</head>
<body style="margin:0; padding:0; background:#f5f0fb; font-family:Arial, sans-serif; color:#2d1b3d;">
    <div style="max-width:600px; margin:40px auto; background:#ffffff; border:1px solid #e6dff0; border-radius:16px; overflow:hidden;">
        <div style="background:linear-gradient(90deg, #583473 0%, #A485BC 100%); padding:24px; text-align:center; color:white;">
            <h1 style="margin:0; font-size:28px;">Klassify</h1>
        </div>

        <div style="padding:32px;">
            <h2 style="margin-top:0; color:#583473;">Solicitud de validación docente</h2>

            <p>
                Se ha registrado un profesor en Klassify indicando que trabaja en su institución.
            </p>

            <p>
                <strong>Profesor:</strong> {{ $teacherRequest->user->name }} {{ $teacherRequest->user->surname }}<br>
                <strong>Email del profesor:</strong> {{ $teacherRequest->user->email }}<br>
                <strong>Institución:</strong> {{ $teacherRequest->institution_name }}<br>
                <strong>Email institucional:</strong> {{ $teacherRequest->institution_email }}<br>
                <strong>Dirección:</strong> {{ $teacherRequest->address }}
            </p>

            <p>
                Si desea confirmar que este profesor pertenece a su institución, pulse el siguiente botón:
            </p>

            <div style="text-align:center; margin:32px 0;">
                <a href="{{ $confirmationUrl }}"
                   style="display:inline-block; background:linear-gradient(90deg, #583473 0%, #A485BC 100%); color:white; text-decoration:none; padding:14px 26px; border-radius:10px; font-weight:bold;">
                    Confirmar profesor
                </a>
            </div>

            <p style="font-size:14px; color:#6f6280;">
                Si usted no reconoce esta solicitud, ignore este correo.
            </p>
        </div>
    </div>
</body>
</html>