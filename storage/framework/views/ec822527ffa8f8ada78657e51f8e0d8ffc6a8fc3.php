<div>
    <?php $__currentLoopData = $cleaners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cleaner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="row profile_row">

        <div class="col-xl-4 col-lg-6 col-md-12 px-0" style="background-color:#fff;">
            <div class="frst_tm">
                <div class="prfl_img_1">
                    <img src="<?php echo e(asset('storage/images/'.$cleaner->image)); ?>">
                </div>
                <div class="folow-us">
                    <ul class="list-unstyled d-flex">
                        <li><span>Share Provider</span></li>
                        <li><a href="#"><svg class="svg-inline--fa fa-facebook" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.8 90.69 226.4 209.3 245V327.7h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.3 482.4 504 379.8 504 256z"></path>
                                </svg><!-- <i class="fa-brands fa-facebook"></i> Font Awesome fontawesome.com --></a></li>
                        <li><a href="#"><svg class="svg-inline--fa fa-twitter" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="twitter" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M459.4 151.7c.325 4.548 .325 9.097 .325 13.65 0 138.7-105.6 298.6-298.6 298.6-59.45 0-114.7-17.22-161.1-47.11 8.447 .974 16.57 1.299 25.34 1.299 49.06 0 94.21-16.57 130.3-44.83-46.13-.975-84.79-31.19-98.11-72.77 6.498 .974 12.99 1.624 19.82 1.624 9.421 0 18.84-1.3 27.61-3.573-48.08-9.747-84.14-51.98-84.14-102.1v-1.299c13.97 7.797 30.21 12.67 47.43 13.32-28.26-18.84-46.78-51.01-46.78-87.39 0-19.49 5.197-37.36 14.29-52.95 51.65 63.67 129.3 105.3 216.4 109.8-1.624-7.797-2.599-15.92-2.599-24.04 0-57.83 46.78-104.9 104.9-104.9 30.21 0 57.5 12.67 76.67 33.14 23.72-4.548 46.46-13.32 66.6-25.34-7.798 24.37-24.37 44.83-46.13 57.83 21.12-2.273 41.58-8.122 60.43-16.24-14.29 20.79-32.16 39.31-52.63 54.25z"></path>
                                </svg><!-- <i class="fa-brands fa-twitter"></i> Font Awesome fontawesome.com --></a></li>
                        <li><a href="#"><svg class="svg-inline--fa fa-instagram" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                                </svg><!-- <i class="fa-brands fa-instagram"></i> Font Awesome fontawesome.com --></a></li>
                        <li><a href="#"><svg class="svg-inline--fa fa-linkedin-in" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="linkedin-in" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M100.3 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.6 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.3 61.9 111.3 142.3V448z"></path>
                                </svg><!-- <i class="fa-brands fa-linkedin-in"></i> Font Awesome fontawesome.com --></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12 ps-0">
            <div class="secnd_tm">
                <h5 class="name_tm"><?php echo e($cleaner->name); ?></h5>
                <span class="link-design-2" style="font-size:14px;">Provider Since 2022</span>
                <div class="text_tm">
                    <div class="rating_row">
                        <p>Rating</p>
                        <div>
                            <img src="assets/images/icons/b_star.svg">
                            <span>4.5</span>
                        </div>
                    </div>
                    <div class="rating_row">
                        <p>Cleanings Done</p>
                        <div>
                            <img src="assets/images/icons/check.svg">
                            <span>4.5</span>
                        </div>
                    </div>
                    <div class="rating_row">
                        <p>Team</p>
                        <div>
                            <img src="assets/images/icons/team.svg">
                            <span>4.5</span>
                        </div>
                    </div>
                    <div class="rating_row">
                        <p>Insured</p>
                        <div>
                            <img src="assets/images/icons/insure.svg">
                            <span>4.5</span>
                        </div>
                    </div>
                    <div class="rating_row">
                        <p>Organic</p>
                        <div>
                            <img src="assets/images/icons/organic.svg">
                            <span>4.5</span>
                        </div>
                    </div>
                </div>
                <div class="btn_msg_cleaner">
                    <a href="message.html" class="btn_msg">Message Cleaner <img src="assets/images/icons/email-2.svg"> </a>
                    <p>Ask a <b>question</b> or request a <b>custom proposal.</b></p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 about-provider">
            <div class="thrd_tm">
                <h3>About Provider</h3>
                <p><?php echo e($cleaner->UserDetails->about); ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /var/www/html/projects/Amandeep Projects/working project/cleaner/cleaner/resources/views/livewire/home/profile.blade.php ENDPATH**/ ?>