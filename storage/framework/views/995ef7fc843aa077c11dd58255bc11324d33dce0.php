<?php if(Session::has('error')): ?>
    <div class="row mr-2 ml-2" >
            <button type="text" class="btn btn-lg btn-block btn-outline-danger mb-2"
                    id="type-error"><?php echo e(Session::get('error')); ?>

            </button>
    </div>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/perm/templl/resources/views/admin/includes/alerts/errors.blade.php ENDPATH**/ ?>