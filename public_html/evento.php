<?php
require_once 'header.php';

use core\controller\Eventos;
use core\controller\Atividades;
use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\sistema\Util;

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : null;

$eventos = new Eventos();
$atividades = new Atividades();


$evento = $eventos->listarEvento($evento_id);
$atividade = $atividades->listarAtividades($evento_id);

(strtotime(date('Y/m/d')) > strtotime($evento->evento_termino)) ? $d = "disabled" : $d = "";
(strtotime(date('Y/m/d')) < strtotime($evento->evento_termino)) ? $verificacaoGerarCeritificado = "disabled": $verificacaoGerarCeritificado = "";

if (!Autenticacao::usuarioAdministrador() && Autenticacao::verificarLogin()) {
	$dados_eventos = [];
    $dados_eventos['busca']['me'] = Autenticacao::getCookieUsuario();
    $dados2 = $eventos->listarEventos($dados_eventos); //eventos que o usuario se inscreveu
} else {
    $dados2 = [];
}

?>

<main role='main' class="mt-3">
	<div class="jumbotron mt-n3" style="border-radius:0px; background:url(assets/imagens/grande.png) no-repeat 0 0">
		<div class="container mb-4">
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-3">
				<h1 class="display-4 text-center"><?= $evento->nome ?></h1>
			</div>
		</div>
		<div class="row mb-3">
			<div class="col-md-5">
				<div class="card border-warning mb-3">
					<div class="row no-gutters align-items-center">
						<div class="col-md-4 mx-auto text-center">
							<i class="fas fa-calendar-week fa-5x"></i>
						</div>
						<div class="col-md-8">
							<div class="card-body">
								<h5 class="card-title">Inscrições</h5>
								<p class="card-text">
									Início: <?= Util::formataDataBR($evento->data_inicio) ?> <br>
									Fim:
									<?php if (strtotime(date('Y/m/d')) >= strtotime($evento->data_termino)) { ?>
										<span class="font-weight-bold text-uppercase text-danger">
											<?= Util::formataDataBR($evento->data_prorrogacao) ?>
										</span> (prorrogado)
									<?php } else {
										echo Util::formataDataBR($evento->data_termino);
									}?>
								</p>
								<p class="card-text"><small class="text-muted">Inscrições somente pelo site</small></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="card border-success mb-3">
					<div class="row no-gutters align-items-center">
						<div class="col-md-4 mx-auto text-center">
							<i class="fas fa-calendar-check fa-5x"></i>
						</div>
						<div class="col-md-8">
							<div class="card-body">
								<h5 class="card-title">Evento</h5>
								<p class="card-text">
									Início: <?= Util::formataDataBR($evento->evento_inicio) ?> <br>
									Fim: <?= Util::formataDataBR($evento->evento_termino) ?>
								</p>
								<p class="card-text"><small class="text-muted">Se programe para esse programa</small></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="btn-group-vertical">
					<?php
                    $cont = 0;

                    if (count($dados2) > 0 && count((array) $dados2['lista_eventos']) > 0 && count((array) $dados2['lista_eventos'][0]) > 0) {
						foreach ($dados2['lista_eventos'] as $j => $evento2) {
							if ($evento->evento_id == $evento2->evento_id) $cont++; ?>
						<?php }
					} if ($cont == 1) {
						$a = "disabled";
						$b = "";
					} else {
						$a = "";
						$b = "disabled";
					}

					if (!Autenticacao::usuarioAdministrador()) { ?>
						<a href="atividades.php?evento_id=<?= $evento->evento_id ?>" class="btn btn-lg btn-outline-dark <?= $a ?> <?= $d ?>">Inscrever-se</a>
					<?php }

					if (Autenticacao::usuarioAdministrador()) { ?>
					<a href="atividades.php?evento_id=<?= $evento->evento_id ?>" class="btn btn-lg btn-outline-dark <?= $a ?>">Atividades</a>
					<a href="cadastro_atividade.php?evento_id=<?= $evento->evento_id ?>" class="btn btn-lg btn-outline-dark <?= $d ?>">
						Adicionar Atividades
					</a>
					<div class="btn-group">
						<a href="cadastro_evento.php?evento_id=<?= $evento->evento_id ?>" class="btn btn-lg btn-outline-dark <?= (strtotime(date('Y/m/d')) > strtotime($evento->evento_inicio)) ? "disabled" : "" ?>">
							Editar
						</a>
						<a href="excluir" class="btn btn-lg btn-outline-danger <?= (strtotime(date('Y/m/d')) > strtotime($evento->evento_inicio)) ? "disabled" : "" ?>" name="excluir" data-toggle="modal" data-target="#confirmModal">
							Excluir
						</a>

					</div>
					<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									Deseja realmente <span class="font-weight-bold text-uppercase text-danger"> Excluir</span> esse evento?
								</div>
								<div class="modal-footer p-2">
									<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Não</button>
									<a id="botao_excluir" href="" class="btn btn-outline-danger" data-evento_id="<?= $evento->evento_id ?>">Sim</a>
								</div>
							</div>
						</div>
					</div>

					<?php } else { ?>
						<a href="atividades.php?evento_id=<?= $evento->evento_id ?>" class="btn btn-lg btn-outline-dark <?= $b ?>">Atividades Inscritas</a>
						<a href="#" id="gerar_certificado" data-evento_id="<?= $evento->evento_id ?>" data-usuario_id="<?= Autenticacao::getCookieUsuario() ?>" class="btn btn-lg btn-outline-dark <?= $verificacaoGerarCeritificado ?>">Certificado</a>
					<?php } ?>
				</div>
			</div>

		</div>

		<div class="row justify-align-center mb-4">
			<div class="col-md-10 offset-md-1">
				<h1 class="display-5 text-center">Sobre o evento</h1>
				<p class="text-justify">
					<?= $evento->descricao ?>
				</p>
			</div>
		</div>

		<ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
			<?php if (count((array) $atividade["total_dias"][0]) > 0) {
				foreach ($atividade["total_dias"] as $i => $dia) { ?>
					<li class="nav-item">
						<a class="nav-link <?= $i == 0 ? "active" : "" ?>" id="dia<?= $i ?>-tab" data-toggle="tab" href="#dia<?= $i ?>" role="tab" aria-controls="dia<?= $i ?>" aria-selected="true">
							<?= Util::dia($dia->data) . "/" . Util::mes($dia->data) ?>
						</a>
					</li>
				<?php }
		} else { ?>
				<li class="nav-item">
					<a class="nav-link active" id="dia1-tab" data-toggle="tab" href="#dia1" role="tab" aria-controls="dia1" aria-selected="true">
						Programação
					</a>
				</li>
			<?php } ?>
		</ul>
		<div class="tab-content mb-5" id="myTabContent">
			<?php foreach ($atividade["total_dias"] as $i => $dia) { ?>
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
							<?php if (count((array) $atividade["lista_atividades"][0]) > 0) {
								foreach ($atividade["lista_atividades"] as $j => $ativ) {
									if (Util::dia($ativ->datahora_inicio) == Util::dia($dia->data)) { ?>
										<tr>
											<td class="align-middle">
												<?= Util::hora($ativ->datahora_inicio) . ":" . Util::min($ativ->datahora_inicio) ?> às
												<?= Util::hora($ativ->datahora_termino) . ":" . Util::min($ativ->datahora_termino) ?>
											</td>
											<td class="align-middle"><?= $ativ->titulo ?></td>
											<td class="align-middle"><?= $ativ->responsavel ?></td>
											<td class="align-middle"><?= $ativ->local ?></td>
										</tr>
									<?php }
							}
						} else { ?>
								<tr>
									<td class="text-center" colspan="4">Em Breve!</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
		</div>
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


<?php
$footer = new Footer();
$footer->setJS('assets/js/evento.js');
require_once 'footer.php';
?>
