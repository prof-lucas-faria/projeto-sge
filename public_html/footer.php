<footer class="text-muted bg-dark p-1">
    <div class="container">
        <p class="float-right">
            <a href="#">Back to top</a>
        </p>
        <p class="text-white">Sistema Gerenciador de Eventos, desenvolvido na matéria de Programação Web II.</p>
        <p class="text-white">SGE &copy; 2019 &nbsp; - &nbsp; Disponível no <a href="https://github.com/pwii-2019-1/sge">GitHub</a></p>
    </div>
</footer>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!--    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<?php

$footer->setJS('assets/js/index.js');

$footer_js = $footer->getJS();

if (count($footer_js) > 0) {
    foreach ($footer_js as $js) {
        echo "<script src=\"" . $js . "\"></script>";
    }
}
?>

</body>
</html>
