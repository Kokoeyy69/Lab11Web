<?php
$db = new Database();
$data = $db->query("SELECT * FROM artikel ORDER BY id DESC");
?>

<h3>Data Artikel</h3>
<a href="/lab11_php_oop/artikel/tambah">+ Tambah Artikel</a>
<br><br>

<table border="1" cellpadding="8" width="100%">
    <tr>
        <th>ID</th>
        <th>Judul</th>
        <th>Isi</th>
        <th>Aksi</th>
    </tr>

    <?php while($row = $data->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['judul'] ?></td>
        <td><?= $row['isi'] ?></td>
        <td>
            <a href="/lab11_php_oop/artikel/ubah?id=<?= $row['id'] ?>">Ubah</a> | 
            <a href="/lab11_php_oop/artikel/hapus?id=<?= $row['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
