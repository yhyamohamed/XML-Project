<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto&family=Signika+Negative&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>XML Project </title>
</head>

<body>
    <?PHP
    session_start();
    $xmldoc = new DOMDocument();
    $xmldoc->load('smple.xml', LIBXML_NOBLANKS);
    ?>
    <div class="search">
        <form action="" method="POST" class="search-form">
            <span class="fa fa-search"></span>
            <input type="text" class="serach-key" name="serach-key" placeholder="Type a keyword and hit enter">
        </form>
    </div>

    <div class="msg"><?php echo $_SESSION['op'] ?? ''; ?></div>

    <div class="container">

        <form id='form-data' action="" method="POST">
            <?php
            unset($_SESSION['op']);
            if (!isset($_SESSION['node-index'])) {
                $_SESSION['node-index'] = 1;
            }
            if (isset($_POST['serach-key'])) {
                require_once('search.php');
                $searchObj = new Operations;
                $nodeName = $searchObj->search($_POST['serach-key']);
                $ele = $xmldoc->getElementsByTagName($nodeName)[0];

                unset($_POST['serach-key']);
            } else {
                $ele = $xmldoc->getElementsByTagName("p" . $_SESSION['node-index'])[0];
            }

            if (!$ele) $ele = $xmldoc->getElementsByTagName("p1")[0];
            foreach ($ele->childNodes as $child) {
            ?>

                <div class="input-data">
                    <label for="<?= $child->nodeName ?>"><?= $child->nodeName ?></label>
                    <input type="text" name="<?= $child->nodeName ?>" id="<?= $child->nodeName ?>" value="<?= $child->nodeValue ?>">
                </div>

            <?php
            }
            ?>
            <div class="input-data">
                <input type="submit" id='ins-btn' value="insert" name="insert">
                <input type="submit" id='up-btn' value="update" name="update">
                <input type="submit" id='delete-btn' value="delete" name="delete">
            </div>
            <div class="input-data">
                <!-- <input type="submit" value="search by name" name="search-by-name"> -->
                <input type="submit" id='prev-btn' value="prev" name="prev">
                <input type="submit" id='next-btn' value="next" name="next">

            </div>
        </form>
        <?php
        if (isset($_POST['update'])) {
            foreach ($ele->childNodes as $child) {
                $node = $ele->getElementsByTagName($child->nodeName)->item(0);
                $node->nodeValue = $_POST[$child->nodeName];
                $ele->replaceChild($node, $node);
            }
            $xmldoc->formatOutput = true;
            $data =  $xmldoc->save('smple.xml');
            $_SESSION['op'] = 'updated';
            header("Location:index.php");
        }
        if (isset($_POST['delete'])) {
            $rootElement = $xmldoc->getElementsByTagName("person")[0];
            $rootElement->removeChild($ele);
            $xmldoc->formatOutput = true;
            $data =  $xmldoc->save('smple.xml');
            $_SESSION['node-index']--;
            $_SESSION['op'] = 'deleted';
            header("Location:index.php");
        }
        ?>
    </div>
    <script>
        $('#next-btn').click((e) => {
            $.ajax({
                url: "transfere.php",
                type: 'POST',
                dataType: "JSON",
                data: {
                    op: 'next',
                    ele: "<?= ($ele->nextSibling->nodeName) ?? null; ?>"

                },
                success: (data) => {
                    console.log(data);
                },
                erro: (err) => {
                    console.log(err);
                }
            });
        })
        $('#prev-btn').click((e) => {

            $.ajax({
                url: "transfere.php",
                type: 'POST',
                dataType: "JSON",
                data: {
                    op: 'prev',
                    ele: "<?= ($ele->previousSibling->nodeName) ?? null; ?>"
                },
                success: (data) => {
                    console.log(data);
                },
                erro: (err) => {
                    console.log(err);
                }
            });
        })
        $('#ins-btn').click((e) => {
            dat = {
                name: $('#name').val(),
                phone: $('#phone').val(),
                address: $('#address').val(),
                email: $('#email').val(),
            }
            $.ajax({
                url: "insertAjax.php",
                type: 'POST',
                dataType: "JSON",
                data: {
                    op: 'insert',
                    data: dat
                },
                success: (data) => {
                    console.log(data);
                },
                erro: (err) => {
                    console.log(err);
                }
            });
        })
    </script>
</body>

</html>