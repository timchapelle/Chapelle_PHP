<?php
/*
 * Modal d'avertissement avant la suppression d'un véhicule 
 * 
 */

foreach ($vehicules as $v):
    ?>
    <!-- Modal -->
    <div id="modalDeleteVehicule_<?= $v->id ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirmer la suppression</h4>
                </div>
                <div class="modal-body">
                    <p>Etes-vous sûr de vouloir supprimer ce véhicule ?</p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-close fa-fw"></i> Surtout pas!</a>
                    <a class="btn btn-warning" href="index.php?p=vehicules.delete&id=<?= $v->id ?>">
                        <i class='fa fa-trash-o fa-fw'></i> Supprimer !
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
