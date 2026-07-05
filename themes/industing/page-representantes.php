<?php

use App\Conn\Read;

$Read ??= new Read();
$Read->exeRead(DB_PAGES, 'WHERE page_name = :nm', 'nm='.$URL[0]);
if (!$Read->getResult()) {
    require REQUIRE_PATH.'/404.php';

    return;
}
\extract($Read->getResult()[0]);

?>

<section class='product-banner'>
    <div class='container'>
        <div class='row'>
            <h1 class='text-center'>
                Representantes
            </h1>
        </div>
    </div>
</section>

<section class='commonSection pdb80 padding-top-50px'>
    <div class='container'>

        <h2 class="text-center">Encontre o <span class="text-red">Representante</span> mais próximo de você</h2>

        <div class="representatives">

            <form class="j_formsubmit" name="form_representatives" id="form_representatives" method="post" action=""
                  enctype="multipart/form-data">

                <input class="callback" type="hidden" name="callback" value="contacts">
                <input class="callback-action" type="hidden" name="callback_action" value="state">

                <select name="uf" class="form-control" id="UF" required="required">
                    <option value="" selected disabled> - SELECIONE SEU ESTADO -</option>
                    <option value="AC"> AC - Acre</option>
                    <option value="AL"> AL - Alagoas</option>
                    <option value="AM"> AM - Amazonas</option>
                    <option value="AP"> AP - Amapá</option>
                    <option value="BA"> BA - Bahia</option>
                    <option value="CE"> CE - Ceará</option>
                    <option value="DF"> DF - Distrito Federal</option>
                    <option value="ES"> ES - Espírito Santo</option>
                    <option value="GO"> GO - Goiás</option>
                    <option value="MA"> MA - Maranhão</option>
                    <option value="MG"> MG - Minas Gerais</option>
                    <option value="MS"> MS - Mato Grosso do Sul</option>
                    <option value="MT"> MT - Mato Grosso</option>
                    <option value="PA"> PA - Pará</option>
                    <option value="PB"> PB - Paraíba</option>
                    <option value="PE"> PE - Pernambuco</option>
                    <option value="PI"> PI - Piauí</option>
                    <option value="PR"> PR - Paraná</option>
                    <option value="RJ"> RJ - Rio de Janeiro</option>
                    <option value="RN"> RN - Rio Grande do Norte</option>
                    <option value="RO"> RO - Rondônia</option>
                    <option value="RR"> RR - Roraima</option>
                    <option value="RS"> RS - Rio Grande do Sul</option>
                    <option value="SC"> SC - Santa Catarina</option>
                    <option value="SE"> SE - Sergipe</option>
                    <option value="SP"> SP - São Paulo</option>
                    <option value="TO"> TO - Tocantins</option>
                </select>

                <select style="display: none; margin-top: 30px" id='CITY' name='city' required
                        class="callback_return_cities">
                </select>

                <figure class="form_spinner">
                    <img class="form_load none" src="<?php echo BASE; ?>/admin/_img/load.gif"/>
                    <br>
                    <p class="form_load none">Carregando...</p>
                </figure>
            </form>
        </div>

        <div class="callback_return_rep representative"></div>

    </div>
</section>
