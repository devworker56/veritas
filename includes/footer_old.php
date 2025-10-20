<?php
// Footer component - Half height with translations
?>
<footer class="footer">
    <div class="container">
        <div class="row g-3"> <!-- Reduced from g-4 to g-3 -->
            <div class="col-lg-4">
                <h5 class="mb-2"> <!-- Reduced from mb-3 to mb-2 -->
                    <i class="fas fa-shield-alt me-2"></i>
                    AidVeritas
                </h5>
                <p class="text-muted small"> <!-- Added small class -->
                    <?php echo $current_language === 'fr' ? 'Transformez votre monnaie en dons vérifiables. Reçus fiscaux consolidés pour chaque donateur.' : 'Transform your change into verifiable donations. Consolidated tax receipts for every donor.'; ?>
                </p>
                <div class="d-flex gap-2"> <!-- Reduced from gap-3 to gap-2 -->
                    <a href="#" class="text-muted"><i class="fab fa-twitter"></i></a> <!-- Removed fa-lg -->
                    <a href="#" class="text-muted"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <h5 class="mb-2"><?php echo $current_language === 'fr' ? 'Navigation' : 'Navigation'; ?></h5> <!-- Reduced from mb-3 to mb-2 -->
                <ul class="list-unstyled">
                    <li class="mb-1"><a href="index.php"><?php echo $lang['home']; ?></a></li> <!-- Reduced from mb-2 to mb-1 -->
                    <li class="mb-1"><a href="about.php"><?php echo $lang['about']; ?></a></li>
                    <li class="mb-1"><a href="charities.php"><?php echo $lang['charities']; ?></a></li>
                    <li class="mb-1"><a href="resources.php"><?php echo $lang['resources']; ?></a></li>
                    <li class="mb-1"><a href="contact.php"><?php echo $lang['contact']; ?></a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <h5 class="mb-2"><?php echo $current_language === 'fr' ? 'Légal' : 'Legal'; ?></h5> <!-- Reduced from mb-3 to mb-2 -->
                <ul class="list-unstyled">
                    <li class="mb-1"><a href="#"><?php echo $current_language === 'fr' ? 'Politique de confidentialité' : 'Privacy Policy'; ?></a></li> <!-- Reduced from mb-2 to mb-1 -->
                    <li class="mb-1"><a href="#"><?php echo $current_language === 'fr' ? 'Conditions d\'utilisation' : 'Terms of Service'; ?></a></li>
                    <li class="mb-1"><a href="#"><?php echo $current_language === 'fr' ? 'Politique des cookies' : 'Cookie Policy'; ?></a></li>
                    <li class="mb-1"><a href="#"><?php echo $current_language === 'fr' ? 'Conformité ARC' : 'CRA Compliance'; ?></a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <h5 class="mb-2"><?php echo $lang['contact']; ?></h5> <!-- Reduced from mb-3 to mb-2 -->
                <ul class="list-unstyled text-muted small"> <!-- Added small class -->
                    <li class="mb-1"><i class="fas fa-envelope me-2"></i> info@aidveritas.com</li> <!-- Reduced from mb-2 to mb-1 -->
                    <li class="mb-1"><i class="fas fa-phone me-2"></i> +1 (514) 123-4567</li>
                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> Montréal, QC</li>
                </ul>
            </div>
        </div>
        <hr class="my-3 border-secondary"> <!-- Reduced from my-4 to my-3 -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-muted mb-0 small">&copy; <?php echo date('Y'); ?> AidVeritas. <?php echo $current_language === 'fr' ? 'Tous droits réservés.' : 'All rights reserved.'; ?></p> <!-- Added small class -->
            </div>
            <div class="col-md-6 text-md-end">
                <span class="security-badge small"> <!-- Added small class -->
                    <i class="fas fa-lock me-1"></i>
                    <?php echo $current_language === 'fr' ? 'Sécurisé et vérifié' : 'Secure and Verified'; ?>
                </span>
            </div>
        </div>
    </div>
</footer>