<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "Script ejecutado<br>";

$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$mensaje = $_POST["mensaje"];


$admin_email = "fe.catalanv@duocuc.cl,jt.valenzuela@duocuc.cl,feli.verdugo@duocuc.cl,vi.cardoza@duocuc.cl"; 

$asunto_admin = "Nuevo mensaje de contacto desde IndieMemes";

$cuerpo_admin = '
<html>
<head>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; }
    .container { max-width: 600px; margin: auto; background: #fff; border-radius: 8px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .header { font-size: 22px; font-weight: bold; color: #222; border-bottom: 2px solid #eeeeee; padding-bottom: 10px; margin-bottom: 20px; }
    .content { font-size: 15px; color: #333; line-height: 1.6; }
    .mensaje-box { background-color: #f1f1f1; padding: 15px; border-radius: 5px; margin-top: 10px; font-style: italic; }
    .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">ðŸ“§ Nuevo mensaje de contacto</div>
    <div class="content">
      <p><strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '</p>
      <p><strong>Correo de contacto:</strong> ' . htmlspecialchars($correo) . '</p>
      <p><strong>Mensaje:</strong></p>
      <div class="mensaje-box">' . nl2br(htmlspecialchars($mensaje)) . '</div>
    </div>
    <div class="footer">
      Este mensaje fue enviado desde el formulario de contacto del sitio IndieMemes.
    </div>
  </div>
</body>
</html>
';

$cabeceras = "MIME-Version: 1.0\r\n";
$cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";
$cabeceras .= "From: Formulario IndieMemes <no-responder@IndieCompany.cl>\r\n";


mail($admin_email, $asunto_admin, $cuerpo_admin, $cabeceras);


$asunto_cliente = "Tu mensaje fue recibido por IndieCompany";

$cuerpo_cliente = '
<html>
<head>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f0fff0; padding: 20px; }
    .container { max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .content { font-size: 15px; color: #333; line-height: 1.6; }
    .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
  </style>
</head>
<body>
  <div class="container">
    <div class="content">
      <p>Hola <strong>' . htmlspecialchars($nombre) . '</strong>,</p>
      <p>Gracias por contactarte con <strong>FerremÃ¡s</strong>. Hemos recibido tu mensaje y te responderemos a la brevedad.</p>
      <p><strong>Tu mensaje:</strong></p>
      <blockquote>' . nl2br(htmlspecialchars($mensaje)) . '</blockquote>
    </div>
    <div class="footer">
      Este es un correo automÃ¡tico de confirmaciÃ³n.
    </div>
  </div>
</body>
</html>
';

mail($correo, $asunto_cliente, $cuerpo_cliente, $cabeceras);


echo "<script>alert('Mensaje enviado correctamente. Gracias por contactarnos.');</script>";
echo "<script>window.location.href = 'index.php';</script>";
?>

