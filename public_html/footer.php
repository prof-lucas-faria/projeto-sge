</main>

<footer class="text-muted bg-dark">
    <div class="container">
        <div>
            <p class="text-white">Sistema Gerenciador de Eventos.</p>
            <p class="text-white">SGE &copy; 2019 - Disponível no
                <a href="https://github.com/prof-lucas-faria/projeto-sge">GitHub</a>
            </p>
        </div>
        <a href="#"><i class="fa fa-arrow-circle-up"></i></a>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

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
