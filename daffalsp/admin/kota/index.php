<?php
$page = "Kota";
session_start();
require 'functions.php';

if (!isset($_SESSION["username"])) {
    echo "
    <script type='text/javascript'>
        alert('Silahkan login terlebih dahulu, ya!');
        window.location = '../../auth/login/index.php';
    </script>
    ";
}

// Tangkap keyword jika ada pencarian
$keyword = $_GET['keyword'] ?? '';

// Ambil data kota berdasarkan pencarian
if ($keyword) {
    $kota = query("SELECT * FROM kota WHERE nama_kota LIKE '%$keyword%'");
} else {
    $kota = query("SELECT * FROM kota");
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Data Kota</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex bg-gray-100">
    <?php require '../../layouts/sidebar.php'; ?>

    <div class="flex-1 mt-16 p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-10">DATA KOTA</h1>
        <div class="flex justify-between items-center mt-4">
            <a href="tambah.php" class="bg-blue-600 font-semibold text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tambah</a>

            <form action="" method="GET" class="flex items-center space-x-2">
                <input type="search" name="keyword" placeholder="Cari kota..." value="<?= htmlspecialchars($keyword); ?>"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 font-semibold rounded-lg">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto mt-6 text-center">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="py-2 px-4 border">No</th>
                        <th class="py-2 px-4 border">Nama Kota</th>
                        <th class="py-2 px-4 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($kota) > 0): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($kota as $data) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border"><?= $no++; ?></td>
                            <td class="py-2 px-4 border"><?= htmlspecialchars($data["nama_kota"]); ?></td>
                            <td class="py-2 px-4 border">
                                <a href="edit.php?id=<?= $data["id_kota"]; ?>" class="text-white hover:underline mr-3 bg-blue-500 px-3 py-1 rounded-lg">Edit</a>
                                <button onclick="konfirmasiHapus(<?= $data['id_kota']; ?>)" class="text-red-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-4 text-gray-500 italic">Data tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `hapus.php?id=${id}`;
                }
            });
        }
    </script>

    <?php if ($flash): ?>
    <script>
        Swal.fire({
            icon: '<?= $flash['status']; ?>',
            title: '<?= $flash['status'] === 'success' ? 'Berhasil!' : 'Gagal!'; ?>',
            text: '<?= $flash['pesan']; ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    <?php endif; ?>
</body>
</html>
