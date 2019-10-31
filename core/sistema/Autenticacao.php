<?php


namespace core\sistema;


use core\model\Usuario;

class Autenticacao {

    const COOKIE_USUARIO = "usuario";
    const COOKIE_ACESSO = "acesso";
    const ORGANIZADOR = 1;
    const ASSISTENTE = 2;
    const AVALIADOR = 3;

    /**
     * Retorna o valor do cookie do usuario logado
     *
     * @return bool|mixed
     */
    public static function getCookieUsuario() {
        if (self::verificarLogin()) {
            return $_COOKIE[self::COOKIE_USUARIO];
        } else return false;
    }

    /**
     * Retorna o valor do cookie de acesso do usuario logado
     *
     * @return bool|mixed
     */
    public static function getCookieAcesso() {
        if (self::verificarLogin()) {
            return $_COOKIE[self::COOKIE_ACESSO];
        } else return false;
    }

    /**
     * Verifica se o usuário já está logado no sistema
     */
    public static function verificarLogin() {
        if (isset($_COOKIE[self::COOKIE_USUARIO]) && isset($_COOKIE[self::COOKIE_ACESSO])) {
            return true;
        } else {
            self::logout();
            return false;
        }
    }

    /**
     * Verifica se o usuário é Administrador
     *
     * @return bool
     */
    public static function usuarioAdministrador() {
        if (isset($_COOKIE[self::COOKIE_USUARIO]) && isset($_COOKIE[self::COOKIE_ACESSO])) {
            $user = new Usuario();

            $usuario = $user->selecionarUsuario($_COOKIE[self::COOKIE_USUARIO])[0];

            if (count((array)$usuario) > 0 && $usuario->admin == 1) {
                return true;
            } else {
                return false;
            }

        } else {
            self::logout();
            return false;
        }
    }

    /**
     * Verifica se o usuário é Organizador
     *
     * @return bool
     */
    public static function usuarioOrganizador() {
        if (isset($_COOKIE[self::COOKIE_USUARIO])) {
            $user = new Usuario();

            $usuario = $user->selecionarUsuario($_COOKIE[self::COOKIE_USUARIO])[0];

            if (count((array)$usuario) > 0 && $usuario->permissao == self::ORGANIZADOR) {
                return true;
            } else {
                return false;
            }

        } else {
            self::logout();
            return false;
        }
    }

    /**
     * Verifica se o usuário é Assistente
     *
     * @return bool
     */
    public static function usuarioAssitente() {
        if (isset($_COOKIE[self::COOKIE_USUARIO])) {
            $user = new Usuario();

            $usuario = $user->selecionarUsuario($_COOKIE[self::COOKIE_USUARIO])[0];

            if (count((array)$usuario) > 0 && $usuario->permissao == self::ASSISTENTE) {
                return true;
            } else {
                return false;
            }

        } else {
            self::logout();
            return false;
        }
    }

    /**
     * Verifica se o usuário é Avaliador
     *
     * @return bool
     */
    public static function usuarioAvaliador() {
        if (isset($_COOKIE[self::COOKIE_USUARIO])) {
            $user = new Usuario();

            $usuario = $user->selecionarUsuario($_COOKIE[self::COOKIE_USUARIO])[0];

            if (count((array)$usuario) > 0 && $usuario->permissao == self::AVALIADOR) {
                return true;
            } else {
                return false;
            }

        } else {
            self::logout();
            return false;
        }
    }

    /**
     * Efetua o login do usuário no sistema
     *
     * @param $usuario_cpf
     * @param $senha
     * @param bool $lembrar
     * @param bool $senha_md5
     * @return bool
     */
    public static function login($usuario_cpf, $senha, $lembrar = false, $senha_md5 = false) {

        $nova_senha = ($senha_md5) ? $senha : md5($senha);

        $user = new Usuario();
        $resultado = $user->autenticarUsuario($usuario_cpf, $nova_senha);

        if (count((array)$resultado) > 0) {
            $usuario_id = $resultado->usuario_id;
        } else {
            return false;
        }

        $lembrar_acesso = $lembrar ? time() + 604800 : null;

        setcookie(self::COOKIE_USUARIO, $usuario_id, $lembrar_acesso, PATH_COOKIE);
        setcookie(self::COOKIE_ACESSO, $nova_senha, $lembrar_acesso, PATH_COOKIE);

        return true;
    }

    /**
     * Efetua o logout no sistema e remove os cookies de acesso
     */
    public static function logout() {
        if (isset($_COOKIE[self::COOKIE_USUARIO]) && isset($_COOKIE[self::COOKIE_ACESSO])) {
            setcookie(self::COOKIE_USUARIO, "", time() - 1, PATH_COOKIE);
            setcookie(self::COOKIE_ACESSO, "", time() - 1, PATH_COOKIE);
        }
    }
}
