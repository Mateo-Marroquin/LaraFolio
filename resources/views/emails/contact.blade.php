<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto - LaraFolio</title>
</head>
<body
    style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; line-height: 1.5; color: #1e293b;">

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 40px 20px;">
    <tr>
        <td align="center">

            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                   style="max-width: 600px; background-color: #ffffff; border: 1px border-solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">

                <tr>
                    <td style="background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%); padding: 32px; text-align: center;">
                        <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.05em;">
                            LaraFolio
                        </h1>
                        <p style="margin: 4px 0 0 0; color: #e0f2fe; font-size: 14px;">
                            Sistema de Portafolios Inteligentes
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 40px 32px;">
                        <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 600; color: #0f172a; letter-spacing: -0.02em;">
                            ¡Has recibido un nuevo mensaje!
                        </h2>
                        <p style="margin: 0 0 24px 0; color: #64748b; font-size: 15px;">
                            Alguien se ha interesado en tu perfil y ha completado el formulario de contacto de tu
                            portafolio público.
                        </p>

                        <table border="0" cellpadding="0" cellspacing="0" width="100%"
                               style="margin-bottom: 24px; background-color: #f1f5f9; border-radius: 12px; padding: 16px;">
                            <tr>
                                <td style="padding: 6px 0; font-size: 14px; color: #475569; width: 30%;"><strong>Remitente:</strong>
                                </td>
                                <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: 500;">{{ $data['name'] }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0; font-size: 14px; color: #475569;"><strong>Correo:</strong>
                                </td>
                                <td style="padding: 6px 0; font-size: 14px; color: #0284c7; font-weight: 500;">
                                    <a href="mailto:{{ $data['email'] }}"
                                       style="color: #0284c7; text-decoration: none;">{{ $data['email'] }}</a>
                                </td>
                            </tr>
                        </table>

                        <h3 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">
                            Mensaje o Propuesta:
                        </h3>
                        <div
                            style="background-color: #fafafa; border-left: 4px solid #0ea5e9; border-radius: 4px; padding: 20px; color: #334155; font-size: 15px; font-style: italic; line-height: 1.6;">
                            {!! nl2br(e($data['message'])) !!}
                        </div>

                        <table border="0" cellpadding="0" cellspacing="0" width="100%"
                               style="margin-top: 32px; text-align: center;">
                            <tr>
                                <td>
                                    <a href="mailto:{{ $data['email'] }}"
                                       style="display: inline-block; background-color: #0284c7; color: #ffffff; font-weight: 600; font-size: 15px; padding: 12px 32px; border-radius: 8px; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);">
                                        Responder Directamente
                                    </a>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <tr>
                    <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px; text-align: center; font-size: 12px; color: #94a3b8;">
                        <p style="margin: 0 0 4px 0;">Este es un correo automático generado por tu aplicación local
                            LaraFolio.</p>
                        <p style="margin: 0;">© {{ date('Y') }} LaraFolio. San Luis Potosí, México.</p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
