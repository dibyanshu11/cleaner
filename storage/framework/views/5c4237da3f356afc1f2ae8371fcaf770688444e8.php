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
            <div class="blue-logo-block text-center max-width-100">
              <a href="#"><img src="assets/images/logo/logo.svg"></a>
            </div>
         </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 no-padd">
          <div class="row no-mrg">
           <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 no-padd">
            <div class="customer-account-forms support_service_section pe-3">   
              <div class="support_tabs">
                  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Past services</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Contact Us</button>
                      </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                    	 <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
				<?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('cleaner.support')->html();
} elseif ($_instance->childHasBeenRendered('L1cVQK4')) {
    $componentId = $_instance->getRenderedChildComponentId('L1cVQK4');
    $componentTag = $_instance->getRenderedChildComponentTagName('L1cVQK4');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('L1cVQK4');
} else {
    $response = \Livewire\Livewire::mount('cleaner.support');
    $html = $response->html();
    $_instance->logRenderedChild('L1cVQK4', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
			</div>
             <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">     
               <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('cleaner.support-contact')->html();
} elseif ($_instance->childHasBeenRendered('NLxU7on')) {
    $componentId = $_instance->getRenderedChildComponentId('NLxU7on');
    $componentTag = $_instance->getRenderedChildComponentTagName('NLxU7on');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('NLxU7on');
} else {
    $response = \Livewire\Livewire::mount('cleaner.support-contact');
    $html = $response->html();
    $_instance->logRenderedChild('NLxU7on', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
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
       </div>   
       </div>
     </div>
   </section>


<?php $__env->stopSection(); ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('assets/js/mdb.min.js')); ?>"></script>
<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'livewire-alert::components.scripts','data' => []] + (isset($attributes) ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('livewire-alert::scripts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/cleaner/resources/views/cleaner/support.blade.php ENDPATH**/ ?>