<div class='modal' id='myModal' style='align-items: center; justify-content: center;'>
	<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
            <?php
            $Read ??= new Read();
            $page = $Read->LinkResult(
                DB_PAGES,
                'page_name',
                'politica-de-protecao-de-dados',
                'page_title, page_content'
            );
            ?>
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title"><?php
                    echo $page['page_title']; ?></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
                <?php
                echo $page['page_content']; ?>
			</div>
			<!-- Modal footer -->
			<div class="modal-footer">
				<label for="privacy" class="btn btn-danger" data-dismiss="modal">
					<i class="fa
                fa-window-close"></i> Fechar</label>
			</div>
		</div>
	</div>
</div>
