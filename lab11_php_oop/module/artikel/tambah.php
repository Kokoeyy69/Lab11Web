<?php
$form = new Form("/lab11_php_oop/artikel/tambah", "Simpan Artikel");
$db  = new Database();

if ($_POST) {
    $db->insert("artikel", [
        "judul" => $_POST['judul'],
        "isi"   => $_POST['isi']
    ]);

    header("Location: /lab11_php_oop/artikel/index");
    exit;
}

$form->addField("judul", "Judul Artikel");
$form->addField("isi", "Isi Artikel", "textarea");

echo "<h3>Tambah Artikel</h3>";
$form->displayForm();
?>
