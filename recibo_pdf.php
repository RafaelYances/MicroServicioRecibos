<?php
require "conexion.php";
date_default_timezone_set('America/Bogota'); 

$id  = (int) $_GET["id"];
$res = mysqli_query($conn, "SELECT * FROM recibos WHERE id = $id");
$r   = mysqli_fetch_assoc($res);

if (!$r) die("Recibo no encontrado.");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recibo #<?= $r['id'] ?></title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Arial, sans-serif;
      background: #f0f0f0;
      display: flex;
      justify-content: center;
      padding: 40px;
    }

    .recibo {
      background: white;
      width: 600px;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.15);
    }

    .encabezado {
      background: #2980b9;
      color: white;
      padding: 20px;
      text-align: center;
      border-radius: 6px;
      margin-bottom: 30px;
    }

    .encabezado h1 { font-size: 24px; }
    .encabezado p  { font-size: 13px; margin-top: 4px; opacity: 0.85; }

    .fila {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #eee;
      font-size: 15px;
    }

    .fila span:first-child { font-weight: bold; color: #555; }
    .fila span:last-child  { color: #222; }

    .total {
      background: #2980b9;
      color: white;
      padding: 16px 20px;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      font-size: 18px;
      font-weight: bold;
      margin-top: 24px;
    }

    .pie {
      text-align: center;
      margin-top: 24px;
      font-size: 12px;
      color: #aaa;
    }

    .botones {
      display: flex;
      gap: 12px;
      justify-content: center;
      margin-top: 28px;
    }

    .btn {
      padding: 10px 24px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      text-decoration: none;
    }

    .btn-blue  { background: #2980b9; color: white; }
    .btn-gray  { background: #ccc;    color: #333;  }

    /* Al imprimir: oculta botones y fondo */
    @media print {
      body { background: white; padding: 0; }
      .recibo { box-shadow: none; }
      .botones { display: none; }
    }
  </style>
</head>
<body>
  <div class="recibo">

    <div class="encabezado">
      <h1>RECIBO DE PAGO</h1>
      <p>Generado el <?= date('d/m/Y H:i') ?></p>
    </div>

    <div class="fila">
      <span>N° Recibo</span>
      <span>#<?= $r['id'] ?></span>
    </div>
    <div class="fila">
      <span>Cédula Cliente</span>
      <span><?= htmlspecialchars($r['id_cliente']) ?></span>
    </div>
    <div class="fila">
      <span>Fecha</span>
      <span><?= date('d/m/Y', strtotime($r['fecha'])) ?></span>
    </div>
    <div class="fila">
      <span>Forma de pago</span>
      <span><?= htmlspecialchars($r['forma_pago']) ?></span>
    </div>

    <div class="total">
      <span>MONTO TOTAL</span>
      <span>$<?= number_format($r['monto'], 2) ?></span>
    </div>

    <div class="pie">
      Este documento es un comprobante de pago válido.
    </div>

    <div class="botones">
      <button class="btn btn-blue" onclick="window.print()">⬇ Descargar / Imprimir PDF</button>
      <a class="btn btn-gray" href="index.php">← Volver</a>
    </div>

  </div>
</body>
</html>