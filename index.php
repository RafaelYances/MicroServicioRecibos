<?php
require "conexion.php";

if (isset($_POST["guardar"])) {
  $id_cliente = preg_replace('/\D/', '', $_POST["id_cliente"]);
  $monto = $_POST["monto"];
  $fecha = $_POST["fecha"];
  $forma_pago = $_POST["forma_pago"];

  if ($fecha > date('Y-m-d')) {
    header("Location: index.php?error=fecha");
    exit;
  }

  mysqli_query($conn, "INSERT INTO recibos (id_cliente, monto, fecha, forma_pago)
                         VALUES ($id_cliente, '$monto', '$fecha', '$forma_pago')");

  $nuevo_id = mysqli_insert_id($conn);
  header("Location: recibo_pdf.php?id=" . $nuevo_id);
  exit;
}

// Eliminar
if (isset($_GET["eliminar"])) {
  mysqli_query($conn, "DELETE FROM recibos WHERE id=" . (int) $_GET["eliminar"]);
  header("Location: index.php");
  exit;
}

// Obtener recibos
$res = mysqli_query($conn, "SELECT * FROM recibos ORDER BY fecha DESC");
$total = 0;
$filas = [];
while ($r = mysqli_fetch_assoc($res)) {
  $total += $r["monto"];
  $filas[] = $r;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Recibos</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="wrap">

    <header class="no-print">
      <h1>Recibos</h1>
      <span><?= date('d/m/Y') ?></span>
    </header>

    <header id="print-header" style="display:none">
      <h1>Listado de Recibos</h1>
      <span>Generado: <?= date('d/m/Y H:i') ?></span>
    </header>

    <div class="card">

      <?php if (isset($_GET["ok"])): ?>
        <div class="aviso no-print">Recibo guardado correctamente.</div>
      <?php endif; ?>

      <!-- Formulario -->
      <form method="POST" class="no-print">
        <div class="form-row">
          <div class="form-group">
            <label>Cédula Cliente</label>
            <input type="number" name="id_cliente" placeholder="" min="1" step="1" required>
          </div>
          <div class="form-group">
            <label>Monto ($)</label>
            <input type="number" name="monto" placeholder="0.00" step="0.01" min="0" required>
          </div>
          <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="form-group">
            <label>Forma de pago</label>
            <select name="forma_pago" required>
              <option value="">Seleccionar…</option>
              <option value="Efectivo">Efectivo</option>
              <option value="Tarjeta">Tarjeta</option>
              <option value="Transferencia">PSE</option>
            </select>
          </div>
        </div>
        <div class="btn-row">
          <button class="btn btn-blue" name="guardar">Guardar recibo</button>
          <button type="button" class="btn btn-green" onclick="generarPDF()">🖨 Generar PDF</button>
        </div>
      </form>

      <hr class="no-print">

      <!-- Tabla -->
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>ID Cliente</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Forma de pago</th>
            <th class="no-print">Descargar</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filas as $r): ?>
            <tr>
              <td><?= $r["id"] ?></td>
              <td><?= htmlspecialchars($r["id_cliente"]) ?></td>
              <td>$<?= number_format($r["monto"], 2) ?></td>
              <td><?= date('d/m/Y', strtotime($r["fecha"])) ?></td>
              <td><span class="badge badge-<?= strtolower($r["forma_pago"]) ?>"><?= $r["forma_pago"] ?></span></td>
              <td class="no-print">
              <a class="btn btn-blue" href="recibo_pdf.php?id=<?= $r['id'] ?>" target="_blank">⬇ PDF</a>
            </td>
          <tbody>
            
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="total-row">Total: $<?= number_format($total, 2) ?></div>

    </div>
  </div>

  <script>
    function generarPDF() {
      document.getElementById('print-header').style.display = 'flex';
      window.print();
      document.getElementById('print-header').style.display = 'none';
    }
  </script>
</body>

</html>