<?php
require_once 'header.php';

use core\controller\Eventos;
use core\controller\Atividades;
use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\sistema\Util;
use core\controller\Tematicas;
use core\controller\Eventos_Tipos;

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : null;

$eventos = new Eventos();
$atividades = new Atividades();
$tematicas = new Tematicas();
$tipos = new Eventos_Tipos();

$dados2 = [];
$evento = "";
$atividade = "";
$lista_tipos = "";

$evento = $eventos->listarEvento($evento_id);
$atividade = $atividades->listarAtividades($evento_id);
$lista_tematicas = $tematicas->listar($evento_id);
$lista_tipos = $tipos->listarEventosTipos($evento_id);

(strtotime(date('Y/m/d')) > strtotime($evento->evento_termino)) ? $d = "disabled" : $d = "";
(strtotime(date('Y/m/d')) < strtotime($evento->evento_termino)) ? $verificacaoGerarCeritificado = "disabled" : $verificacaoGerarCeritificado = "";

if (!Autenticacao::usuarioAdministrador() && Autenticacao::verificarLogin()) {
	$dados_eventos = [];
	$dados_eventos['busca']['me'] = Autenticacao::getCookieUsuario();
	$dados2 = $eventos->listarEventos($dados_eventos); //eventos que o usuario se inscreveu
}

?>


<main role="main">
	<!--	<div class="jumbotron mt-n5" style="height: 250px; border-radius:0px; background:url(assets/imagens/grande2.jpg) no-repeat 0 0"></div>-->

	<div class="container mt-5">
		<div class="card shadow-sm mb-2 p-4">
			<h1 class="text-center font-weight-bold mb-4"><?= $evento->nome ?></h1>
			<div class="row">
				<div class="col-md-12">
					<div class="row justify-align-center ">
						<div class="col-md-10 offset-md-1">
							<p class="text-justify">
								<?= $evento->descricao ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>



		<!-- Apenas um teste 1 -->



		<div class="container ">
			<div class="row">
				<!-- Evento  -->
				<div class="col-md-4">
					<div class="card  border-0 h-100">
						<h5 class="card-title text-center font-weight-bold mt-3 text-uppercase">Data</h5>
						<div class="card-body text-center h-100">
							<div class="col-md-12  text-primary">
								<i class="fas fa-calendar-check"></i>
								<?= Util::formataDataExtenso($evento->evento_inicio) ?>
							</div>


							<div class="col-md-12 mt-1  text-primary">
								<i class="fas fa-calendar-times"></i>
								<?= Util::formataDataExtenso($evento->evento_termino) ?>
							</div>

							<div class="col-md-12 mt-1  text-primary">
								<i class="fas fa-map-marker-alt"></i>
								<?= $evento->local ?>
							</div>

						</div>
						<div class="card-body col-md-12 text-center">
							<p><small class="text-muted">Inscreva-se nas atividades do evento</small></p>
							<a href="#programacao" class="btn btn-outline-dark <?= $a ?> <?= $d ?>">Confira a programação!</a>
						</div>
					</div>
				</div>
				<!-- Evento  -->

				<!-- Inscrições -->
				<div class="col-md-4">
					<div class="card  border-0 h-100 ">
						<h5 class="card-title text-center font-weight-bold mt-3 text-uppercase  ">Inscrições</h5>
						<div class="card-body text-center text-primary">
							<div class="col-md-12">
								<i class="fas fa-calendar-check mr-2"></i>
								<?= Util::formataDataExtenso($evento->data_inicio) ?>
							</div>

							<div class="col-md-12">
								<i class="fas fa-calendar-times mr-2"></i>
								<?php
								//colocar a data de prorrogação caso a data atual seja igual ou maior

								if (strtotime(date('Y/m/d')) >= strtotime($evento->data_termino)) {
									echo Util::formataDataExtenso($evento->data_prorrogacao);
								} else {
									echo Util::formataDataExtenso($evento->data_termino);
								}
								?>
							</div>
							<div class="col-md-12 align-self-center align-text-middle mt-2">
								<!-- colocar badge dos temas do evento -->

								<?php
								$cores = ['primary', 'secondary', 'success', 'danger', 'warning', 'dark'];
								$i = 0;
								foreach ($lista_tematicas as $key => $tematica) {
									?>
									<span class="badge badge-<?= $cores[$i++] ?>"> <?= $tematica->descricao ?> </span>
								<?php
									if ($i > 5) $i = 0;
								}
								?>

							</div>


							<?php
							$cont = 0;

							if (isset($dados2['lista_eventos']) && count((array) $dados2['lista_eventos'][0]) > 0) {
								foreach ($dados2['lista_eventos'] as $j => $evento2) {
									if ($evento->evento_id == $evento2->evento_id) $cont++;
								}
							}

							if ($cont == 1) {
								$a = "disabled";
								$b = "";
							} else {
								$a = "";
								$b = "disabled";
							}
							?>


						</div>

						<div class="card-body col-md-12 text-center">
							<p><small class="text-muted">Inscrições apenas pelo site.</small></p>
							<a href="atividades.php?evento_id=<?= $evento->evento_id ?>" class="btn btn-outline-dark <?= $a ?> <?= $d ?>">Inscrever-se</a>
						</div>
					</div>
				</div>

				<!-- Inscrições -->



				<?php
				if (isset($evento->data_inicio_sub) && $evento->data_inicio_sub != null) {
					?>
					<!-- Submissão -->
					<div class="col-md-4 mt-2">
						<div class="card  border-0 h-100">
							<h5 class="card-title text-center font-weight-bold mt-3 text-uppercase ">Submissões</h5>
							<div class="card-body text-center text-primary h-100 ">
								<div class="col-md-12">
									<i class="fas fa-calendar-check mr-2"></i>
									<?= $evento->data_inicio_sub != NULL ? Util::formataDataExtenso($evento->data_inicio_sub) : "" ?>
								</div>

								<div class="col-md-12">
									<i class="fas fa-calendar-times mr-2"></i>
									<?= $evento->data_termino_sub != NULL ? Util::formataDataExtenso($evento->data_termino_sub) : "" ?>
								</div>

							</div>

							<div class="card-body col-md-12 text-center ">
								<p><small class="text-muted">Inscrições apenas pelo site.</small></p>
								<a href="cadastro_trabalho.php?evento_id=<?= $evento->evento_id ?>" class="btn btn-outline-dark <?= $a ?> <?= $d ?>">Envie seu Trabalho</a>
							</div>
						</div>
					</div>
					<!-- Submissão -->
				<?php
				} else {

					?>
					<!-- Submissão Indisponível -->
					<div class="col-md-4">
						<div class="card  border-0 h-100">
							<h5 class="card-title text-center font-weight-bold mt-3 text-uppercase ">Submissões</h5>
							<div class="card-body text-center text-primary h-100">
								<div class="col-md-12 ">
									<div class="text-center font-weight-bold">Não haverá submissão de trabalhos.</div>
								</div>
							</div>
							<div class="card-body col-md-12 text-center mb-0">
								<p><small class="text-muted">Este evento não possibilita o envio de trabalhos.</small></p>
								<a href="" class="btn btn-outline-dark disabled">Submissão Indisponível</a>
							</div>
						</div>
					</div>

					<!--  Submissão Indisponível -->
				<?php
				}
				?>




			</div>

		</div>

		<div class="card shadow-sm p-4 mt-4 mb-3" id="programacao">
			<h2 class="text-center">Programação</h2><br>
			<p class="text-center mb-4">Atividades</p>

			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
						<?php
						if (count((array) $atividade["total_dias"][0]) > 0) {
							foreach ($atividade["total_dias"] as $i => $dia) {
								?>
								<li class="nav-item">
									<a class="nav-link <?= $i == 0 ? "active" : "" ?>" id="dia<?= $i ?>-tab" data-toggle="tab" href="#dia<?= $i ?>" role="tab" aria-controls="dia<?= $i ?>" aria-selected="true">
										<?= Util::dia($dia->data) . "/" . Util::mes($dia->data) ?>
									</a>
								</li>
							<?php
								}
							} else {
								?>
							<li class="nav-item">
								<a class="nav-link active" id="dia1-tab" data-toggle="tab" href="#dia1" role="tab" aria-controls="dia1" aria-selected="true">
									Programação
								</a>
							</li>
						<?php
						}
						?>
					</ul>

					<div class="tab-content" id="myTabContent">
						<?php
						foreach ($atividade["total_dias"] as $i => $dia) {
							?>
							<div class="tab-pane fade <?= $i == 0 ? "show active" : "" ?>" id="dia<?= $i ?>" role="tabpanel" aria-labelledby="dia<?= $i ?>-tab">
								<table class="table table-hover table-bordered">
									<thead class="thead-dark">
										<tr>
											<th class="col-md-2">Horário</th>
											<th class="col-md-6">Título</th>
											<th class="col-md-2">Responsável</th>
											<th class="col-md-2">Local</th>
										</tr>
									</thead>
									<tbody>
										<?php
											if (count((array) $atividade["lista_atividades"][0]) > 0) {
												foreach ($atividade["lista_atividades"] as $j => $ativ) {
													if (Util::dia($ativ->datahora_inicio) == Util::dia($dia->data)) {
														?>
													<tr>
														<td class="align-middle">
															<?= Util::hora($ativ->datahora_inicio) . ":" . Util::min($ativ->datahora_inicio) ?> às
															<?= Util::hora($ativ->datahora_termino) . ":" . Util::min($ativ->datahora_termino) ?>
														</td>
														<td class="align-middle"><?= $ativ->titulo ?></td>
														<td class="align-middle"><?= $ativ->responsavel ?></td>
														<td class="align-middle"><?= $ativ->local ?></td>
													</tr>
											<?php
														}
													}
												} else {
													?>
											<tr>
												<td class="text-center" colspan="4">Em Breve!</td>
											</tr>
										<?php
											}
											?>
									</tbody>
								</table>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>




		<?php
		if (isset($evento->data_inicio_sub) && $evento->data_inicio_sub != null) {
			?>
			<!-- Tipos de Trabalhos -->

			<div class="album py-3 mt-2 card mb-3">
				<h2 class="text-center">Trabalhos</h2>

				<div class="container">
					<div class="row">
						<div class="col-md-8 offset-2">
							<div class="card border-0 text-center">
								<div class="card-body">

									<div class="row text-primary">
										<div class="col-md-12">
											<h4>
												<i class="fas fa-calendar-check mr-2"></i>
												<?= $evento->data_inicio_sub != NULL ? Util::formataDataExtenso($evento->data_inicio_sub) : "" ?>
											</h4>
										</div>
									</div>

									<div class="row mt-2 text-primary">
										<div class="col-md-12">
											<h4>
												<i class="fas fa-calendar-times mr-2"></i>
												<?= $evento->data_termino_sub != NULL ? Util::formataDataExtenso($evento->data_termino_sub) : "" ?>
											</h4>
										</div>
									</div>

									<h4>
										<p class="card-text"><small class="text-muted">Submissões apenas pelo site.</small></p>
									</h4>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="container">
					<div class="row">



						<?php
							foreach ($lista_tipos as $key => $tipo) {
								// print_r($tipo);
								?>

							<div class="col-md-6">
								<div class="card mt-2  border-0">
									<div class="card-body">
										<h5 class="font-weight-bold text-center text-uppercase mb-3"><?= $tipo->descricao ?></h5>
										<p class="card-text">As demais regras sobre o trabalho estarão contidas dentro do modelo de escrita. Os modelos estão disponíveis para download abaixo:</p>
										<h5 class="display-5 text-center mb-3">Modelos</h5>
										<div class="d-flex justify-content-center align-items-center">
											<button class="btn btn-sm btn-outline-dark col-md-6 mr-2" name='download_modelo' data-path=<?= (isset($tipo->modelo_escrita)) ? '"' . $tipo->modelo_escrita . '"' : '""' . 'disabled=' . '"disabled"' ?>> <i class="fas fa-download mr-1"></i>Escrita</button>
											<button class="btn btn-sm btn-outline-dark col-md-6" name='download_modelo' id="download_apresentacao" data-path=<?= (isset($tipo->modelo_apresentacao)) ? '"' . $tipo->modelo_apresentacao . '"' : '""' . 'disabled=' . '"disabled"' ?>><i class="fas fa-download  mr-2"></i>Apresentação</button>
										</div>
										<div class="text-center mt-4">
											<!-- <button class=" btn-secondary  mr-2">Submeter</button> -->
											<a href="cadastro_trabalho.php?evento_id=<?= $evento->evento_id ?>" class="btn col-md-7 btn-outline-dark mr-2 <?= $a ?> <?= $d ?>">Envie seu Trabalho</a>

										</div>

									</div>
								</div>
							</div>

						<?php
							}
							?>

					</div>
				</div>
			</div>

	</div>
<?php
}
?>


<!-- Toast Erro Exclusao -->
<div class="toast" id="msg_exclusao_erro" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000" style="position: absolute; top: 4rem; right: 1rem;">
	<div class="toast-header">
		<strong class="mr-auto">Houve um erro!</strong>
		<small>Agora</small>
		<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="toast-body">
		Desculpe, não conseguimos excluir o evento, tente novamente.
	</div>
	<div class="card-footer text-muted bg-warning p-1"></div>
</div>
<!-- Toast -->
</div>
</main>

<script>

</script>

<?php
$footer = new Footer();
$footer->setJS('assets/js/evento.js');
require_once 'footer.php';
?>