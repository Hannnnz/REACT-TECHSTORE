<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update - GENSHIN CRUD</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url();?>public/resources/logo.jpg">
    <link rel="stylesheet" href="<?= base_url();?>public/css/home.css">
</head>
<body class="form" style="background-image: url('<?= base_url();?>public/resources/jenshin.gif'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">
    <h1>Update Character</h1>
    <div class="login-box">
    <form action="<?=site_url('update/'.$char['id']);?>" method="post" enctype="multipart/form-data">
        <img id="preview" src="<?=base_url().'uploads/'.$char['pic'];?>" alt="Current Pic" width="120"><br>
        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" title="(Optional) Keep empty to retain current picture" size="20" />
        <input type="text" name="name" id="name" placeholder="Character Name" value="<?=html_escape($char['name']);?>" required><br><br>
        <label for="class" style="color:white;">Class</label>
        <select name="class" id="class" required>
            <option value="Pyro" <?php if(html_escape($char['class']) == "Pyro") echo "selected"; ?>>Pyro</option>
            <option value="Cryo" <?php if(html_escape($char['class']) == "Cryo") echo "selected"; ?>>Cryo</option>
            <option value="Geo" <?php if(html_escape($char['class']) == "Geo") echo "selected"; ?>>Geo</option>
            <option value="Hydro" <?php if(html_escape($char['class']) == "Hydro") echo "selected"; ?>>Hydro</option>
            <option value="Anemo" <?php if(html_escape($char['class']) == "Anemo") echo "selected"; ?>>Anemo</option>
            <option value="Electro" <?php if(html_escape($char['class']) == "Electro") echo "selected"; ?>>Electro</option>
            <option value="Dendro" <?php if(html_escape($char['class']) == "Dendro") echo "selected"; ?>>Dendro</option>
        </select><br><br>
        <input type="submit" value="Update"></button>
    </form>
</div><br>

    <script>
        window.addEventListener('load', function () {
        document.getElementById('name').focus();
        });

        document.getElementById("name").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            document.getElementById("class").focus();
            event.preventDefault(); 
        }
    });

        document.getElementById("fileToUpload").addEventListener("change", function(e){
        const preview = document.getElementById("preview");
        const file = e.target.files[0];
        if(file){
            preview.src = URL.createObjectURL(file);
        } else{
            preview.src = "<?=base_url().'uploads/'.$char['pic'];?>"; 
        }
    });
    </script>
</body>
</html>