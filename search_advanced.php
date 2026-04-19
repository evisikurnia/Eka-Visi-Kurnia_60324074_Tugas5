<?php
// ================= DATA BUKU =================
$buku_list = [
    ["kode"=>"BK001","judul"=>"Algoritma Dasar","kategori"=>"Informatika","pengarang"=>"Andi","tahun"=>2020,"harga"=>75000,"stok"=>5],
    ["kode"=>"BK002","judul"=>"PHP Dasar","kategori"=>"Informatika","pengarang"=>"Budi","tahun"=>2022,"harga"=>120000,"stok"=>0],
    ["kode"=>"BK003","judul"=>"Basis Data","kategori"=>"Database","pengarang"=>"Sari","tahun"=>2019,"harga"=>90000,"stok"=>3],
    ["kode"=>"BK004","judul"=>"Matematika Diskrit","kategori"=>"Matematika","pengarang"=>"Rudi","tahun"=>2021,"harga"=>85000,"stok"=>2],
    ["kode"=>"BK005","judul"=>"Jaringan Komputer","kategori"=>"Networking","pengarang"=>"Dian","tahun"=>2018,"harga"=>110000,"stok"=>4],
    ["kode"=>"BK006","judul"=>"AI Dasar","kategori"=>"AI","pengarang"=>"Kevin","tahun"=>2023,"harga"=>150000,"stok"=>6],
    ["kode"=>"BK007","judul"=>"UI UX Design","kategori"=>"Design","pengarang"=>"Nina","tahun"=>2022,"harga"=>95000,"stok"=>1],
    ["kode"=>"BK008","judul"=>"Data Science","kategori"=>"AI","pengarang"=>"Lina","tahun"=>2021,"harga"=>135000,"stok"=>0],
    ["kode"=>"BK009","judul"=>"Mobile App","kategori"=>"Mobile","pengarang"=>"Agus","tahun"=>2020,"harga"=>100000,"stok"=>7],
    ["kode"=>"BK010","judul"=>"Sistem Operasi","kategori"=>"Informatika","pengarang"=>"Yoga","tahun"=>2017,"harga"=>80000,"stok"=>3],
];

// ================= GET DATA =================
$keyword = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';
$sort = $_GET['sort'] ?? 'judul';

// ================= FILTER =================
$hasil = array_filter($buku_list, function($b) use ($keyword,$kategori,$min,$max){

    if ($keyword &&
        stripos($b['judul'],$keyword) === false &&
        stripos($b['pengarang'],$keyword) === false) {
        return false;
    }

    if ($kategori && $b['kategori'] != $kategori) return false;

    if ($min && $b['harga'] < $min) return false;

    if ($max && $b['harga'] > $max) return false;

    return true;
});

// ================= SORTING =================
usort($hasil, function($a,$b) use ($sort){
    if ($sort == "harga") return $a['harga'] <=> $b['harga'];
    if ($sort == "tahun") return $b['tahun'] <=> $a['tahun'];
    return strcmp($a['judul'],$b['judul']);
});

// ================= PAGINATION =================
$perPage = 10;
$page = $_GET['page'] ?? 1;
$start = ($page - 1) * $perPage;
$total = count($hasil);
$hasil = array_slice($hasil, $start, $perPage);
$pages = ceil($total / $perPage);

// ================= HIGHLIGHT =================
function highlight($text,$keyword){
    if(!$keyword) return $text;
    return str_ireplace($keyword,"<mark>$keyword</mark>",$text);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Pencarian Buku</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<h3>Pencarian Buku</h3>

<!-- FORM -->
<form method="GET" class="card p-3 mb-3">

<div class="row">
    <div class="col">
        <input type="text" name="keyword" class="form-control" placeholder="Keyword" value="<?= $keyword ?>">
    </div>

    <div class="col">
        <select name="kategori" class="form-control">
            <option value="">Semua Kategori</option>
            <option>Informatika</option>
            <option>Database</option>
            <option>AI</option>
            <option>Networking</option>
            <option>Design</option>
            <option>Mobile</option>
            <option>Matematika</option>
        </select>
    </div>

    <div class="col">
        <input type="number" name="min" class="form-control" placeholder="Min Harga" value="<?= $min ?>">
    </div>

    <div class="col">
        <input type="number" name="max" class="form-control" placeholder="Max Harga" value="<?= $max ?>">
    </div>
</div>

<br>

<div class="row">
    <div class="col">
        <select name="sort" class="form-control">
            <option value="judul">Sort Judul</option>
            <option value="harga">Sort Harga</option>
            <option value="tahun">Sort Tahun</option>
        </select>
    </div>
</div>

<br>

<button class="btn btn-primary">Cari</button>

</form>

<!-- HASIL -->
<h5>Hasil ditemukan: <?= $total ?></h5>

<table class="table table-bordered table-striped">
<tr>
    <th>Kode</th>
    <th>Judul</th>
    <th>Kategori</th>
    <th>Pengarang</th>
    <th>Tahun</th>
    <th>Harga</th>
    <th>Stok</th>
</tr>

<?php foreach($hasil as $b): ?>
<tr>
    <td><?= $b['kode'] ?></td>
    <td><?= highlight($b['judul'],$keyword) ?></td>
    <td><?= $b['kategori'] ?></td>
    <td><?= highlight($b['pengarang'],$keyword) ?></td>
    <td><?= $b['tahun'] ?></td>
    <td>Rp<?= number_format($b['harga']) ?></td>
    <td><?= $b['stok'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<!-- PAGINATION -->
<nav>
<ul class="pagination">
<?php for($i=1;$i<=$pages;$i++): ?>
<li class="page-item">
<a class="page-link" href="?page=<?= $i ?>&keyword=<?= $keyword ?>&kategori=<?= $kategori ?>&min=<?= $min ?>&max=<?= $max ?>&sort=<?= $sort ?>">
<?= $i ?>
</a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>

</body>
</html>