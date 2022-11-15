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
                            <a href="javascript::void(0)"><img src="<?php echo e(asset('assets/images/logo/logo.svg')); ?>"></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 no-padd">
                    <div class="row no-mrg">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 no-padd cleaner_availability_section">
                            <div class="customer-account-forms ">
                                <div class="form-headeing-second border-bottom pb-3 top_heading">
                                    <h3 class="mb-0">Set Availability</h3>
                                    <a href="#" class="link-design-2">Time Zone: -5:00 CST, current time 11:11am</a>
                                </div>

                                <?php echo $__env->make('cleaner.availability.jobs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                <?php echo $__env->make('cleaner.availability.time', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                <?php echo $__env->make('cleaner.availability.buffer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                
                                <?php echo $__env->make('cleaner.availability.prescheduled', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
<div class="layoutHours" style="display: none;">
    <div class="time-input-addon layout layout-0key0">
        <?php echo Form::time('day[0day0][data][0key0][from_time]', null, ['class' => ($errors->has('from_time') ? ' is-invalid' : '')]); ?>

        <?php echo $errors->first('from_time', '<span class="help-block">:message</span>'); ?>

        <span class="d-none d-md-inline">-</span>
        <?php echo Form::time('day[0day0][data][0key0][to_time]', null, ['class' => ($errors->has('to_time') ? ' is-invalid' : '')]); ?>

        <?php echo $errors->first('to_time', '<span class="help-block">:message</span>'); ?>

        
        <button type="button" class="border-0 bg-none btn-empty deleteDom" data-id="0key0" data-day="0day0">
            <img src="<?php echo e(asset('assets/images/icons/delete_2.svg')); ?>">
        </button>
    </div>
</div>


<script>


    $(document).on('click', '.form-switch', function(){
        var day = $(this).attr('data-day');
        $('.availbility_cover.'+day).toggleClass('show');
    });


    $('.add-time-slots').on('click', function (){
        let day = $(this).attr('data-day');
        layoutHoursBind(day);
    });

    function layoutHoursBind(day){

        var countHour = $('.addNewHoursLayout.'+day+' .layout').length;
        countHour = countHour + 1;
        
        var html = $('.layoutHours').html();
        //...
        html = html.replace(/0key0/g, countHour);
        html = html.replace(/0day0/g, day);


        //...
        $('.addNewHoursLayout.'+day).append(html);

    }

    $('.deleteLayout').on('click', function (){
        let day = $(this).attr('data-day');
        let id = $(this).attr('data-id');

        //...

        $('.addNewHoursLayout.'+day+' .layout-'+id+' .delete').val('yes');
        $('.addNewHoursLayout.'+day+' .layout-'+id).hide();
    });


    $(document).on('click', '.deleteDom', function (){
        let day = $(this).attr('data-day');
        let id = $(this).attr('data-id');

        $('.addNewHoursLayout.'+day+' .layout-'+id).hide();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.cleanerapp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/projects/Amandeep Projects/working project/cleaner/cleaner/resources/views/cleaner/availability/index.blade.php ENDPATH**/ ?>