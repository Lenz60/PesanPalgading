<?php 
if (session_status() === PHP_SESSION_NONE) {
    ob_start();
    session_start();
    ?>
        <!DOCTYPE html>
        <html> 
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <!-- CSS -->
            <link rel="stylesheet" href="style.css">
            <!-- Bootstrap -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">    
            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>    
            <title>Admin Pesan Palgading</title>
        </head>
        <?php
        if(isset($_SESSION['Id']) && isset($_SESSION['Nama'])){
            ?>  
            <body class="bg">
                <div class="container-fluid">
                    <div class="row flex-nowrap">
                        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark" style="height: auto;">
                            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                                    <span class="fs-5 d-none d-sm-inline"><?php print "Selamat Datang\n".$_SESSION['Nama'] ?></span>
                                </a>   
                                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                                    <li class="nav-item">
                                        <span id="clock"></span>
                                    </li>
                                    <li class="nav-item">
                                        <br>
                                        <p style="font-family: sans-serif;">Auto Refresh Berjalan</p>
                                        <p style= "font-family: sans-serif; color:green;">Halaman akan refresh otomatis setiap 10 detik</p>
                                    </li>
                                    <li class="nav-item">
                                        <a href="index2.php" class="nav-link align-middle px-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-stop" viewBox="0 0 16 16">
                                        <path d="M3.5 5A1.5 1.5 0 0 1 5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5zM5 4.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 .5.5h6a.5.5 0 0 0 .5-.5V5a.5.5 0 0 0-.5-.5H5z"/>
                                        </svg> 
                                        <span class="ms-1 d-none d-sm-inline">Stop Refresh</span>
                                        </a>
                                    </li>
                                </ul>
                                <hr>
                                    <div class="dropdown pb-4">
                                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="./Resources/user.svg" alt="hugenerd" width="30" height="30" class="rounded-circle">
                                            <span class="d-none d-sm-inline mx-1"><?php print $_SESSION['Nama'] ?></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                                            <li>
                                                <a class="dropdown-item" href="logout.php" >Logout</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col py-3">
                                <!-- Incoming Table -->
                                <div class="row align-items-center" style="margin-left: 10px; max-width: 50%;">
                                    <table class="table table-bordered border-dark">
                                        <?php 
                                        require("connect.php");
                                        $notable = 0;
                                        ?>
                                        <tr>
                                            <?php 
                                                $stmt = $conn->prepare ("SELECT COUNT(Status_order) FROM orders WHERE Status_order= 'Disiapkan'");
                                                $stmt->execute();
                                                $stmt->store_result();
                                                if($stmt->num_rows > 0){
                                                    $stmt->bind_result($colspan);
                                                    $stmt->fetch();
                                                    ?> <th class="table-dark text-center" colspan=<?php print $colspan ?>>Pesanan Baru</th>
                                                    <?php
                                                }

                                                if (!isset($_SESSION['lastcount'])){
                                                    $check = $_SESSION['lastcount'] = $colspan;
                                                }
                                                else {
                                                    $check = $_SESSION['lastcount'];
                                                }
                                                
                                                if($colspan > $check){
                                                    $check = $_SESSION['lastcount'] = $colspan;
                                                    ?>
                                                    <iframe src="notification.mp3" allow="autoplay" style="display:none" id="iframeAudio"></iframe> 
                                                    <audio id="player" autoplay loop><source src="./Resources/notification.mp3" type="audio/mp3"></audio>
                                                    <?php
                                                }
                                                else {
                                                    
                                                }
                                            ?> 
                                        </tr>
                                        <tr style="background-color: green;">
                                            <?php
                                                $stmt = $conn->prepare ("SELECT g.No_meja, p.Kategori_produk FROM orders o 
                                                                                                JOIN guest_order gord ON o.Kode_Guest_Order = gord.Kode_guest_order 
                                                                                                JOIN guest g ON gord.Kode_Meja = g.Kode_meja 
                                                                                                JOIN product_order po ON o.Kode_Produk_Order = po.Kode_produk_order 
                                                                                                JOIN product p ON po.Kode_Produk = p.Kode_produk 
                                                                                                JOIN topping_order tord ON po.Kode_produk_order = tord.Kode_Produk_Order 
                                                                                                JOIN topping t ON tord.Kode_Topping = t.Kode_topping 
                                                                        WHERE o.Status_order = 'Disiapkan' GROUP BY o.Kode_order ORDER BY gord.Kode_Guest_Order DESC");
                                                $stmt->execute();
                                                $resultIncomingOrder = $stmt->get_result();
                                                while ($rowIncoming = $resultIncomingOrder -> fetch_array(MYSQLI_ASSOC)){
                                                    ?>
                                                    <td class="sizetables3">
                                                        <form method="POST" action="index.php" class="hiddenform">
                                                            <input type="hidden" name="notable" value=<?php print $rowIncoming["No_meja"] ?>>
                                                            <button type="submit" name="notable" value=<?php print $rowIncoming["No_meja"] ?> class="link-button">
                                                                <?php print $rowIncoming["No_meja"] ?> 
                                                                <br> 
                                                                <?php print $rowIncoming["Kategori_produk"] ?>
                                                            </button>
                                                        </form>  
                                                    </td>
                                                <?php }
                                                ?>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!-- Table Location -->
                                <div class="row align-items-center" style="margin-left: 50px;">
                                    <table class="table table-bordered border-dark" style="max-width: 500px; background-color:white">
                                        <!--  //| Lokasi Meja  -->
                                        <!-- //* If Nomor Meja is clicked then show the Select Order on the next Table -->
                                        <tr>
                                        
                                            <th class="table-dark text-center" colspan="5" scope="colgroup" >Lokasi Meja</th>
                                        </tr>
                                        <tr>
                                            <?php     
                                                //* Incoming Order set Color
                                                $table1Status = "white";
                                                $table2Status = "white";
                                                $table3Status = "white";
                                                $table4Status = "white";
                                                $table6Status = "white";
                                                $table7Status = "white";
                                                $table9Status = "white";
                                                $table10Status = "white";
                                                $table11Status = "white";
                                                $table12Status = "white";
                                                $table13Status = "white";
                                                
                                                // Display Ordered Table as green
                                                $stmt = $conn->prepare ("SELECT g.No_meja, o.Status_order
                                                                        FROM orders o JOIN guest_order gord ON o.Kode_Guest_Order = gord.Kode_guest_order JOIN guest g ON gord.Kode_Meja = g.Kode_meja
                                                                                        JOIN product_order po ON o.Kode_Produk_Order = po.Kode_produk_order
                                                                                        JOIN product p ON po.Kode_Produk = p.Kode_produk
                                                                                        JOIN topping_order tord ON po.Kode_produk_order = tord.Kode_Produk_Order
                                                                                        JOIN topping t ON tord.Kode_Topping = t.Kode_topping
                                                                        WHERE NOT o.Status_order = 'Selesai' GROUP BY o.Kode_order ORDER BY gord.Kode_Guest_Order DESC;");
                                                $stmt->execute();
                                                $resultTableColor = $stmt -> get_result();
                                                while ($RowTable = $resultTableColor -> fetch_array(MYSQLI_ASSOC)){
                                                    
                                                    if ($RowTable['No_meja'] == "1"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table1Status = "yellow";
                                                        }
                                                        else {
                                                            $table1Status = "green";
                                                        } 
                                                    }
                                                    else if($RowTable['No_meja'] == "2"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table2Status = "yellow";
                                                        }
                                                        else {
                                                            $table2Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "3"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table3Status = "yellow";
                                                        }
                                                        else {
                                                            $table3Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "4"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table4Status = "yellow";
                                                        }
                                                        else {
                                                            $table4Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "5"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table5Status = "yellow";
                                                        }
                                                        else {
                                                            $table5Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "6"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table6Status = "yellow";
                                                        }
                                                        else {
                                                            $table6Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "7"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table7Status = "yellow";
                                                        }
                                                        else {
                                                            $table7Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "8"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table8Status = "yellow";
                                                        }
                                                        else {
                                                            $table8Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "9"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table9Status = "yellow";
                                                        }
                                                        else {
                                                            $table9Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "10"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table10Status = "yellow";
                                                        }
                                                        else {
                                                            $table10Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "11"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table11Status = "yellow";
                                                        }
                                                        else {
                                                            $table11Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "12"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table12Status = "yellow";
                                                        }
                                                        else {
                                                            $table12Status = "green";
                                                        } 
                                                    }
                                                    else if ($RowTable['No_meja'] == "13"){
                                                        if($RowTable['Status_order'] == "Belum Dibayar"){
                                                            $table13Status = "yellow";
                                                        }
                                                        else {
                                                            $table13Status = "green";
                                                        } 
                                                    }
                                                    else {
                                                        
                                                    }
                                                }
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="sizetables1" style="background-color: <?php echo $table1Status ?>;" >
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="1">
                                                        <button type="submit" name="notable" value="1" class="link-button">1</button>
                                                    </form>
                                                </td>
                                                <td style="background-color: white;"></td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td class="sizetables1" style="background-color: <?php echo $table10Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="10">
                                                        <button type="submit" name="notable" value="10" class="link-button">10</button>
                                                    </form>
                                                </td>
                                                <td class="sizetables2" rowspan="2" style="background-color: <?php echo $table9Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="9">
                                                        <button type="submit" name="notable" value="9" class="link-button">8/9</button>
                                                    </form>
                                                </td>
                                                <td class="sizetables2" rowspan="2" style="background-color: <?php echo $table6Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="6">
                                                        <button type="submit" name="notable" value="6" class="link-button">5/6</button>
                                                    </form>
                                                </td>
                                                <td class="sizetables1" style="background-color: <?php echo $table2Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="2">
                                                        <button type="submit" name="notable" value="2" class="link-button">2</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            
                                            <tr class="sizetables1">
                                                <td class="sizetables2" style="background-color: <?php echo $table13Status ?>;" rowspan="2" >
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="13">
                                                        <button type="submit" name="notable" value="13" class="link-button">13</button>
                                                    </form>
                                                </td>
                                                <td style="padding-top: 20px;background-color: <?php echo $table11Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="11">
                                                        <button type="submit" name="notable" value="11" class="link-button">11</button>
                                                    </form>
                                                </td>
                                                <td style="padding-top: 20px;background-color: <?php echo $table3Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="3">
                                                        <button type="submit" name="notable" value="3" class="link-button">3</button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <tr class="sizetables1">
                                                <td style="padding-top: 20px;background-color: <?php echo $table12Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="12">
                                                        <button type="submit" name="notable" value="12" class="link-button">12</button>
                                                    </form>
                                                </td>
                                                <td style="background-color: white;"></td>
                                                <td style="padding-top: 20px;background-color: <?php echo $table7Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="7">
                                                        <button type="submit" name="notable" value="7" class="link-button">7</button>
                                                    </form>
                                                </td>
                                                <td style="padding-top: 20px;background-color: <?php echo $table4Status ?>;">
                                                    <form method="POST" action="index.php" class="hiddenform">
                                                        <input type="hidden" name="notable" value="4">
                                                        <button type="submit" name="notable" value="4" class="link-button">4</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </tr>
                                    </table>
                                </div>
                                <!-- End of Table Meja -->
                                <!-- Table Detail Order -->
                                <div class="row">
                                    <!-- Table Detail Order -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered border-dark table-striped table-hover text-center" style="width: 1000px; background-color:white">
                                            <tr>
                                                <?php
                                                    require("connect.php");
                                                ?>
                                                <th class="table-dark" colspan="10">Detail Order</th>
                                                <tr>
                                                    <th>Lokasi</th>
                                                    <th>Jumlah Pesanan</th>
                                                    <th>Nama Makanan</th>
                                                    <th>Tipe Makanan</th>
                                                    <th>Topping</th>
                                                    <th>Total Bayar</th>
                                                    <th>Status</th>
                                                    <th>Konfirmasi Pesanan</th>
                                                </tr>
                                                <tr>
                                                    <?php 
                                                        if (isset ($_POST['notable']) == null ){
                                                        }
                                                        else {
                                                            $stmt = $conn->prepare("SELECT g.No_meja,po.Jumlah_Produk_PO, o.Kode_order,p.Nama_produk,p.Tipe_produk, t.Nama_topping, (p.Harga_produk*po.Jumlah_Produk_PO) + SUM(t.Harga_topping) AS 'Total Bayar', o.Status_order 
                                                                                    FROM orders o JOIN guest_order gord ON o.Kode_Guest_Order = gord.Kode_guest_order JOIN guest g ON gord.Kode_Meja = g.Kode_meja
                                                                                                            JOIN product_order po ON o.Kode_Produk_Order = po.Kode_produk_order
                                                                                                            JOIN product p ON po.Kode_Produk = p.Kode_produk
                                                                                                            JOIN topping_order tord ON po.Kode_produk_order = tord.Kode_Produk_Order
                                                                                                            JOIN topping t ON tord.Kode_Topping = t.Kode_topping
                                                                                    WHERE g.No_meja = '".$_POST['notable']."' AND NOT o.Status_order = 'Selesai' GROUP BY o.Kode_order;");
                                                            $stmt->execute();
                                                            
                                                            $resultDetailOrder = $stmt->get_result();
                                                            while ($rowDetail = $resultDetailOrder -> fetch_array(MYSQLI_ASSOC)){
                                                                ?>
                                                                <tbody>
                                                                <?php
                                                                    //* Count the Topping name for span
                                                                    $stmt = $conn->prepare ("SELECT COUNT(t.Nama_topping) FROM orders o 
                                                                                                                            JOIN product_order po ON o.Kode_Produk_Order = po.Kode_produk_order 
                                                                                                                            JOIN topping_order tord ON po.Kode_produk_order = tord.Kode_Produk_Order 
                                                                                                                            JOIN topping t ON tord.Kode_Topping = t.Kode_topping WHERE o.Kode_order = '".$rowDetail['Kode_order']."'");
                                                                    $stmt->execute();
                                                                    $stmt->store_result();
                                                                    if($stmt->num_rows > 0){
                                                                        $stmt->bind_result($rowspanStats);
                                                                        $stmt->fetch();
                                                                    }
                                                                    ?>
                                                                    <?php
                                                                    $stmt = $conn->prepare("SELECT t.Nama_topping FROM orders o JOIN product_order po ON o.Kode_Produk_Order = po.Kode_produk_order 
                                                                                                                    JOIN topping_order tord ON po.Kode_produk_order = tord.Kode_Produk_Order
                                                                                                                    JOIN topping t ON tord.Kode_Topping = t.Kode_topping
                                                                                            WHERE o.Kode_order = '".$rowDetail["Kode_order"]."'");
                                                                    $stmt->execute();
                                                                    $resultTopping = $stmt->get_result();
                                                                    ?>
                                                                    <tr>
                                                                        <?php 
                                                                        if($rowDetail["Status_order"] == "Disiapkan"){
                                                                            ?>
                                                                            <td rowspan="<?php print $rowspanStats?>">Meja <?php print $rowDetail["No_meja"] ?></td> <!-- //? Fill with Order Data// -->
                                                                            <td rowspan="<?php print $rowspanStats?>"><?php print $rowDetail["Jumlah_Produk_PO"] ?></td> <!-- //? Fill with Order Data// -->
                                                                            <td rowspan="<?php print $rowspanStats?>"><?php print $rowDetail["Nama_produk"] ?></td>
                                                                            <td rowspan="<?php print $rowspanStats?>"><?php print $rowDetail["Tipe_produk"] ?></td>
                                                                            <td rowspan="<?php print $rowspanStats?>">
                                                                            <table class="text-center"> <!-- //? Topping -->
                                                                                <?php          
                                                                                while ($rowTopping = $resultTopping -> fetch_array(MYSQLI_ASSOC)){
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <?php print $rowTopping["Nama_topping"] ?>
                                                                                        </td> 
                                                                                    </tr>                                                                                                                                       
                                                                                <?php
                                                                                }
                                                                                ?>                                                               
                                                                            </table>
                                                                            </td>
                                                                            <td >Rp <?php print $rowDetail["Total Bayar"] ?></td>
                                                                            <td ><?php print $rowDetail["Status_order"] ?></td>
                                                                                    <td>
                                                                                        <form method="POST" action="konfirmasi.php" class="hiddenform">
                                                                                            <input type="hidden" name="Status" value="Konfirmasi">
                                                                                            <input type="hidden" name="KodeOrder" value="<?php print $rowDetail['Kode_order'] ?>">
                                                                                            <button type="submit" name="Button" value="Konfirmasi" class="statusorderbutton">Konfirmasi</button>
                                                                                        </form>
                                                                            </td>
                                                                            <?php
                                                                        }
                                                                        else {
                                                                            ?>
                                                                            <td style="background-color: yellow;" rowspan="<?php print $rowspanStats?>">Meja <?php print $rowDetail["No_meja"] ?></td> <!-- //? Fill with Order Data// -->
                                                                            <td style="background-color: yellow;" rowspan="<?php print $rowspanStats?>"><?php print $rowDetail["Jumlah_Produk_PO"] ?></td> <!-- //? Fill with Order Data// -->
                                                                            <td style="background-color: yellow;" rowspan="<?php print $rowspanStats?>"><?php print $rowDetail["Nama_produk"] ?></td>
                                                                            <td style="background-color: yellow;" rowspan="<?php print $rowspanStats?>"><?php print $rowDetail["Tipe_produk"] ?></td>
                                                                            <td style="background-color: yellow;" rowspan="<?php print $rowspanStats?>">
                                                                            <table class="text-center"> <!-- //? Topping -->
                                                                                <?php          
                                                                                while ($rowTopping = $resultTopping -> fetch_array(MYSQLI_ASSOC)){
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <?php print $rowTopping["Nama_topping"] ?>
                                                                                        </td> 
                                                                                    </tr>                                                                                                                                       
                                                                                <?php
                                                                                }
                                                                                ?>                                                               
                                                                            </table>
                                                                            </td>
                                                                            <td style="background-color: yellow;" >Rp <?php print $rowDetail["Total Bayar"] ?></td>
                                                                            <td style="background-color: yellow;"><?php print $rowDetail["Status_order"] ?></td>
                                                                                <td style="background-color: yellow;">
                                                                                    <form method="POST" action="konfirmasi.php" class="hiddenform">
                                                                                            <input type="hidden" name="Status" value="Selesai">
                                                                                            <input type="hidden" name="KodeOrder" value="<?php print $rowDetail['Kode_order'] ?>">
                                                                                            <button type="submit" name="Button" value="Selesai" class="statusorderbutton">Selesai</button>
                                                                                    </form>
                                                                                </td>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                </tbody>
                                                            <?php }
                                                        }
                                                        ?>
                                                </tr>
                                            </tr>
                                        </table>
                                    </div>
                                </div>     
                            </div>      
                        </div>
                    </div>
                </div>
            </body>
            <?php
        }
        else {
            echo "<script>alert('Session Telah Berakhir silahkan login kembali')</script>";
            header("Location: login.php");
        }
        ?>
        <script type="text/javascript">
            var clockElement = document.getElementById('clock');
            var options = {hour:"numeric", minute:"numeric", second:"2-digit", weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
            var today = new Date();

            function clock() {
                clockElement.textContent = today.toLocaleDateString("id-ID",options).toString();
            }

            setInterval(clock, 10);
        </script>
        <script language = "javascript">
            setTimeout(function(){
                window.location.reload(1);
            }, 5000);
        </script>
        </html>
    <?php
    ob_end_flush();
}
else {
    echo "text here";
    ob_start();
    echo "<script>alert('Session Telah Berakhir silahkan login kembali')</script>";
    header("Location: login.php");
    ob_end_flush();
    exit();
}
?>