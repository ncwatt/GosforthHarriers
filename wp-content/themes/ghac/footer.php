    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-4 order-1">
                    <h5>Gosforth Harriers &amp; Athletics Club</h5>
                    <p>Broadway West Playing Fields</p>
                    <p>Broadway West</p>
                    <p>Gosforth</p>
                    <p>Newcastle upon Tyne</p>
                    <p>NE3 2HY</p>
                    <ul class="follow-icons">
                        <!--<li><a href="#" class="fab fa-facebook-f"></a>&nbsp;</li>-->
                        <li><a href="https://www.facebook.com/groups/165444858443/" target="_blank"><i class="bi bi-facebook"></i></a></li>
                        <li><a href="https://twitter.com/Gosforth_HandAC" target="_blank"><i class="bi bi-twitter-x"></i></a></li>
                        <li><a href="https://www.instagram.com/gosforthharriers" target="_blank"><i class="bi bi-instagram"></i></a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 order-3 order-md-2">
                    <div class="affiliates">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/england-athletics-logo.png" class="small-logo" alt="England Athletics" />
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/british-athletics-logo.png" class="medium-logo" alt="British Athletics" />
                    </div>
                </div>
                <div class="col-6 col-md-4 order-2 order-md-3">
                    <div class="useful-links">
                        <h5>Useful Links</h5>
                        <?php 
					        wp_nav_menu (
						        array ( 
							        'theme_location'	=> 'useful-links',
							        'container'			=> 'li',
							        'container_class'	=> '',
							        'menu_class'		=> 'useful-links-ul',
							        'add_li_class'		=> ''
						        )
					        ); 
				        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="copyright">
                        <p>&copy; Copyright <?php echo date("Y"); ?> | All rights reserved | Privacy Policy</p>
                        <p>Powered by <a href="https://wordpress.org/" target="_blank">WordPress</a> | Theme by <a href="https://gtctek.co.uk/" target="_blank">Gtctek</a> | Admin <a href="/wp-admin">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>