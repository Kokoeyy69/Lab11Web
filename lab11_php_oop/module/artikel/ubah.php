<?php
$db = new Database();
$id = $_GET['id'];

$data = $db->get("artikel", "id=$id");
$form = new Form("/lab11_php_oop/artikel/ubah?id=$id", "Update Artikel");

if ($_POST) {
    $db->update("artikel", [
        "judul" => $_POST['judul'],
        "isi"   => $_POST['isi']
    ], "id=$id");

    header("Location: /lab11_php_oop/artikel/index");
    exit;
}

$form->addField("judul", "Judul Artikel");
$form->addField("isi", "Isi Artikel", "textarea");

echo "<h3>Ubah Artikel</h3>";
?>

<script>
window.onload = function() {
    document.getElementsByName('judul')[0].value = "<?= $data['judul'] ?>";
    document.getElementsByName('isi')[0].value = "<?= $data['isi'] ?>";
};
</script>

<?php
$form->displayForm();
?>
