<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create - GENSHIN CRUD</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url();?>public/resources/logo.jpg">
    <link rel="stylesheet" href="<?= base_url();?>public/css/home.css">
</head>
<body class="form" style="background-image: url('<?= base_url();?>public/resources/jenshin.gif'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">
    <h1>Add Character</h1>
    <div class="login-box">
    <form action="<?=site_url('create'); ?>" method="post" enctype="multipart/form-data">
        <img id="preview" width="120"><br>
        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" size="20" required />
        <input type="text" name="name" id="name" placeholder="Character Name" required><br><br>
        <label for="class" style="color:white;">Class</label>
        <select name="class" id="class" required>
            <option value="Pyro">Pyro</option>
            <option value="Cryo">Cryo</option>
            <option value="Geo">Geo</option>
            <option value="Hydro">Hydro</option>
            <option value="Anemo">Anemo</option>
            <option value="Electro">Electro</option>
            <option value="Dendro">Dendro</option>
        </select><br><br>
        <input type="submit" value="Add"></button>
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
            preview.src = ""; 
        }
    });
    </script>
</body>
</html>