<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    *                                                                        *
    *   @author Prefeitura Municipal de Itajaí                               *
    *   @updated 29/03/2007                                                  *
    *   Pacote: i-PLB Software Público Livre e Brasileiro                    *
    *                                                                        *
    *   Copyright (C) 2006  PMI - Prefeitura Municipal de Itajaí             *
    *                       ctima@itajai.sc.gov.br                           *
    *                                                                        *
    *   Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
    *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
    *   publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
    *   Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
    *                                                                        *
    *   Este programa  é distribuído na expectativa de ser útil, mas SEM     *
    *   QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
    *   ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
    *   sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
    *                                                                        *
    *   Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
    *   junto  com  este  programa. Se não, escreva para a Free Software     *
    *   Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
    *   02111-1307, USA.                                                     *
    *                                                                        *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once ("include/clsBase.inc.php");
require_once ("include/clsCadastro.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/pmieducar/geral.inc.php" );
require_once 'include/modules/clsModulesAuditoriaGeral.inc.php';

class clsIndexBase extends clsBase
{
    function Formular()
    {
        $this->SetTitulo( "{$this->_instituicao} i-Educar - Autor" );
        $this->processoAp = "594";
        $this->addEstilo('localizacaoSistema');
    }
}

class indice extends clsCadastro
{
    /**
     * Referencia pega da session para o idpes do usuario atual
     *
     * @var int
     */
    var $pessoa_logada;

    var $cod_acervo_autor;
    var $ref_usuario_exc;
    var $ref_usuario_cad;
    var $nm_autor;
    var $descricao;
    var $data_cadastro;
    var $data_exclusao;
    var $ativo;
    var $ref_cod_biblioteca;
    var $ref_cod_escola;
    var $ref_cod_instituicao;

    function Inicializar()
    {
        $retorno = "Novo";

        $this->cod_acervo_autor=$_GET["cod_acervo_autor"];

        $obj_permissoes = new clsPermissoes();
        $obj_permissoes->permissao_cadastra( 594, $this->pessoa_logada, 11,  "educar_acervo_autor_lst.php" );

        if( is_numeric( $this->cod_acervo_autor ) )
        {

            $obj = new clsPmieducarAcervoAutor( $this->cod_acervo_autor );
            $registro  = $obj->detalhe();
            if( $registro )
            {
                foreach( $registro AS $campo => $val )  // passa todos os valores obtidos no registro para atributos do objeto
                    $this->$campo = $val;

                $this->nm_autor = stripslashes($this->nm_autor);
                $this->nm_autor = htmlspecialchars($this->nm_autor);

                $obj_permissoes = new clsPermissoes();
                if( $obj_permissoes->permissao_excluir( 594, $this->pessoa_logada, 11 ) )
                {
                    $this->fexcluir = true;
                }

                if( class_exists( "clsPmieducarBiblioteca" ) )
                {
                    $obj_ref_cod_biblioteca = new clsPmieducarBiblioteca( $registro["ref_cod_biblioteca"] );
                    $det_ref_cod_biblioteca = $obj_ref_cod_biblioteca->detalhe();
                    $this->ref_cod_instituicao = $det_ref_cod_biblioteca["ref_cod_instituicao"];
                    $this->ref_cod_escola = $det_ref_cod_biblioteca["ref_cod_escola"];

                }
                else
                {
                    $registro["ref_cod_biblioteca"] = "Erro na gera&ccedil;&atilde;o";
                }

                    $retorno = "Editar";
            }
        }
        $this->url_cancelar = ($retorno == "Editar") ? "educar_acervo_autor_det.php?cod_acervo_autor={$registro["cod_acervo_autor"]}" : "educar_acervo_autor_lst.php";
        $this->nome_url_cancelar = "Cancelar";

        $nomeMenu = $retorno == "Editar" ? $retorno : "Cadastrar";

        $this->breadcrumb($nomeMenu . ' autor', [
            url('intranet/educar_biblioteca_index.php') => 'Biblioteca',
        ]);

        return $retorno;
    }

    function Gerar()
    {
        // primary keys
        $this->campoOculto( "cod_acervo_autor", $this->cod_acervo_autor );


        // foreign keys
        $get_escola     = 1;
        $escola_obrigatorio = false;
        $get_biblioteca = 1;
        $instituicao_obrigatorio = true;
        $biblioteca_obrigatorio = true;
        include("include/pmieducar/educar_campo_lista.php");

        // text
        $this->campoTexto( "nm_autor", "Autor", $this->nm_autor, 30, 255, true );
        $this->campoMemo( "descricao", "Descri&ccedil;&atilde;o", $this->descricao, 60, 5, false );
        $obj_permissoes = new clsPermissoes();
        $nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);


    }

    function Novo()
    {


        $this->nm_autor = addslashes($this->nm_autor);

        $obj_permissoes = new clsPermissoes();
        $obj_permissoes->permissao_cadastra( 594, $this->pessoa_logada, 11,  "educar_acervo_autor_lst.php" );


        $obj = new clsPmieducarAcervoAutor( null, null, $this->pessoa_logada, $this->nm_autor, $this->descricao, null, null, 1,$this->ref_cod_biblioteca );
        $this->cod_acervo_autor = $cadastrou = $obj->cadastra();
        if( $cadastrou )
        {
      $obj->cod_acervo_autor = $this->cod_acervo_autor;
      $acervo_autor = $obj->detalhe();
      $auditoria = new clsModulesAuditoriaGeral("acervo_autor", $this->pessoa_logada, $this->cod_acervo_autor);
      $auditoria->inclusao($acervo_autor);
            $this->mensagem .= "Cadastro efetuado com sucesso.<br>";

            $this->simpleRedirect('educar_acervo_assunto_lst.php');
        }

        $this->mensagem = "Cadastro n&atilde;o realizado.<br>";

        return false;
    }

    function Editar()
    {


        $this->nm_autor = addslashes($this->nm_autor);

        $obj_permissoes = new clsPermissoes();
        $obj_permissoes->permissao_cadastra( 594, $this->pessoa_logada, 11,  "educar_acervo_autor_lst.php" );


        $obj = new clsPmieducarAcervoAutor($this->cod_acervo_autor, $this->pessoa_logada, null, $this->nm_autor, $this->descricao, null, null, 1,$this->ref_cod_biblioteca);
    $detalheAntigo = $obj->detalhe();;
        $editou = $obj->edita();
        if( $editou )
        {
      $obj->cod_acervo_autor = $this->cod_acervo_autor;
      $detalheAtual = $obj->detalhe();
      $auditoria = new clsModulesAuditoriaGeral("acervo_autor", $this->pessoa_logada, $this->cod_acervo_autor);
      $auditoria->alteracao($detalheAntigo, $detalheAtual);
            $this->mensagem .= "Edi&ccedil;&atilde;o efetuada com sucesso.<br>";

            $this->simpleRedirect('educar_acervo_assunto_lst.php');
        }

        $this->mensagem = "Edi&ccedil;&atilde;o n&atilde;o realizada.<br>";

        return false;
    }

    function Excluir()
    {


        $obj_permissoes = new clsPermissoes();
        $obj_permissoes->permissao_excluir( 594, $this->pessoa_logada, 11,  "educar_acervo_autor_lst.php" );


        $obj = new clsPmieducarAcervoAutor($this->cod_acervo_autor, $this->pessoa_logada, null, null, null, null, null, 0,$this->ref_cod_biblioteca);
    $detalhe = $obj->detalhe();
        $excluiu = $obj->excluir();
        if( $excluiu )
        {

      $auditoria = new clsModulesAuditoriaGeral("acervo_autor", $this->pessoa_logada, $this->cod_acervo_autor);
      $auditoria->exclusao($detalhe);
            $this->mensagem .= "Exclus&atilde;o efetuada com sucesso.<br>";

            $this->simpleRedirect('educar_acervo_assunto_lst.php');
        }

        $this->mensagem = "Exclus&atilde;o n&atilde;o realizada.<br>";

        return false;
    }
}

// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na clsBase
$pagina->addForm( $miolo );
// gera o html
$pagina->MakeAll();
?>
