<style>
    body{
        margin: 0px;
    }
    div{
        display:inline-block;
        float:left;
        /*color:#fff;*/
        font-size:40px;
    }

    .one{
        width: 50%;
        height: 100%;
        background: url(http://webstyling.se/wp-content/uploads/2013/09/package.png) no-repeat center;
        background-size: cover;
    }

    .two{
        width: 50%;
        height: 100%;
        background: url(https://unsplash.it/800) no-repeat center;
        background-size: cover;
    }
    .two:hover {
        background-color: yellow;
    }



</style>
<div class="one" onclick="wysylka()">

    <div class="img-wrapper" style="color:white; font-family: sans-serif;
    text-align: center;">
        Baza Wysyłka
    </div>

</div>
<div class="two" onclick="badania()">
    <div class="img-wrapper">

    </div>

</div>

<script>

    function wysylka() {
        window.location.replace("/wysylka");
    }

    function badania() {
        window.location.replace("/badania");
    }

</script>