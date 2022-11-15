<?php $__env->startSection('content'); ?>

<section class="light-banner customer-account-page" style="background-image: url('assets/images/white-pattern.png')">
   <div class="container">
      <div class="customer-white-wrapper">
         <div class="row no-mrg">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 no-padd">
               <div class="blue-bg-wrapper bar_left">
                  <div class="blue-bg-heading">
                     <h4>Settings</h4>
                  </div>
                  <?php echo $__env->make('layouts.common.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

               </div>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 no-padd">
               <div class="row no-mrg">
                  <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 no-padd">
                     <div class="customer-account-forms">
                        <div class="form-headeing-second">
                           <h4>Account Photo</h4>
                        </div>
                        
                        <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('customer.account')->html();
} elseif ($_instance->childHasBeenRendered('ehyiEV1')) {
    $componentId = $_instance->getRenderedChildComponentId('ehyiEV1');
    $componentTag = $_instance->getRenderedChildComponentTagName('ehyiEV1');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('ehyiEV1');
} else {
    $response = \Livewire\Livewire::mount('customer.account');
    $html = $response->html();
    $_instance->logRenderedChild('ehyiEV1', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/projects/Amandeep Projects/working project/cleaner/cleaner/resources/views/customer/account.blade.php ENDPATH**/ ?>