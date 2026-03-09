<?php
/**
 * Template for Single Company Profile
 *
 * Displays detailed company information including logo, description,
 * gallery, and contact information with responsive design.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Get company data
$company_data = Reforestamos_Company_Manager::get_company_data( get_the_ID() );
?>

<div class="company-profile-wrapper">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			
			<article id="company-<?php the_ID(); ?>" <?php post_class( 'company-profile' ); ?>>
				
				<!-- Company Header -->
				<header class="company-header">
					<div class="row align-items-center">
						<div class="col-lg-3 col-md-4 mb-4 mb-md-0">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="company-logo">
									<?php the_post_thumbnail( 'company-logo-large', array( 'class' => 'img-fluid' ) ); ?>
								</div>
							<?php else : ?>
								<div class="company-logo-placeholder">
									<span class="dashicons dashicons-building"></span>
								</div>
							<?php endif; ?>
						</div>
						
						<div class="col-lg-9 col-md-8">
							<h1 class="company-title"><?php the_title(); ?></h1>
							
							<?php if ( $company_data['industry'] ) : ?>
								<div class="company-meta">
									<span class="meta-label"><?php esc_html_e( 'Industria:', 'reforestamos-empresas' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $company_data['industry'] ); ?></span>
								</div>
							<?php endif; ?>
							
							<?php if ( $company_data['partnership'] ) : ?>
								<div class="company-meta">
									<span class="meta-label"><?php esc_html_e( 'Tipo de Colaboración:', 'reforestamos-empresas' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $company_data['partnership'] ); ?></span>
								</div>
							<?php endif; ?>
							
							<?php if ( $company_data['website'] ) : ?>
								<div class="company-website">
									<a href="<?php echo esc_url( $company_data['website'] ); ?>" 
									   target="_blank" 
									   rel="noopener noreferrer"
									   class="btn btn-primary">
										<span class="dashicons dashicons-admin-site"></span>
										<?php esc_html_e( 'Visitar Sitio Web', 'reforestamos-empresas' ); ?>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</header>

				<!-- Company Description -->
				<?php if ( get_the_content() ) : ?>
					<section class="company-description">
						<h2><?php esc_html_e( 'Acerca de la Empresa', 'reforestamos-empresas' ); ?></h2>
						<div class="description-content">
							<?php the_content(); ?>
						</div>
					</section>
				<?php endif; ?>

				<!-- Company Gallery -->
				<?php
				$gallery_ids = $company_data['gallery'];
				if ( $gallery_ids ) :
					$ids = explode( ',', $gallery_ids );
					if ( ! empty( $ids ) ) :
				?>
					<section class="company-gallery">
						<h2><?php esc_html_e( 'Galería', 'reforestamos-empresas' ); ?></h2>
						<div class="row g-3">
							<?php foreach ( $ids as $image_id ) : 
								$image_url = wp_get_attachment_image_url( $image_id, 'medium' );
								$image_full = wp_get_attachment_image_url( $image_id, 'full' );
								$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
								
								if ( $image_url ) :
							?>
								<div class="col-lg-4 col-md-6 col-sm-6">
									<a href="<?php echo esc_url( $image_full ); ?>" 
									   data-lightbox="company-gallery" 
									   data-title="<?php echo esc_attr( $image_alt ); ?>"
									   class="gallery-item">
										<img src="<?php echo esc_url( $image_url ); ?>" 
											 alt="<?php echo esc_attr( $image_alt ); ?>" 
											 class="img-fluid"
											 loading="lazy">
									</a>
								</div>
							<?php 
								endif;
							endforeach; 
							?>
						</div>
					</section>
				<?php 
					endif;
				endif; 
				?>

				<!-- Contact Information -->
				<?php if ( $company_data['email'] || $company_data['phone'] || $company_data['address'] ) : ?>
					<section class="company-contact">
						<h2><?php esc_html_e( 'Información de Contacto', 'reforestamos-empresas' ); ?></h2>
						<div class="row">
							<?php if ( $company_data['email'] ) : ?>
								<div class="col-md-4 mb-3">
									<div class="contact-item">
										<span class="dashicons dashicons-email"></span>
										<div class="contact-info">
											<strong><?php esc_html_e( 'Email', 'reforestamos-empresas' ); ?></strong>
											<a href="mailto:<?php echo esc_attr( $company_data['email'] ); ?>">
												<?php echo esc_html( $company_data['email'] ); ?>
											</a>
										</div>
									</div>
								</div>
							<?php endif; ?>
							
							<?php if ( $company_data['phone'] ) : ?>
								<div class="col-md-4 mb-3">
									<div class="contact-item">
										<span class="dashicons dashicons-phone"></span>
										<div class="contact-info">
											<strong><?php esc_html_e( 'Teléfono', 'reforestamos-empresas' ); ?></strong>
											<a href="tel:<?php echo esc_attr( $company_data['phone'] ); ?>">
												<?php echo esc_html( $company_data['phone'] ); ?>
											</a>
										</div>
									</div>
								</div>
							<?php endif; ?>
							
							<?php if ( $company_data['address'] ) : ?>
								<div class="col-md-4 mb-3">
									<div class="contact-item">
										<span class="dashicons dashicons-location"></span>
										<div class="contact-info">
											<strong><?php esc_html_e( 'Dirección', 'reforestamos-empresas' ); ?></strong>
											<p><?php echo nl2br( esc_html( $company_data['address'] ) ); ?></p>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</section>
				<?php endif; ?>

				<!-- Back to Companies -->
				<div class="company-navigation">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'empresas' ) ); ?>" class="btn btn-secondary">
						<span class="dashicons dashicons-arrow-left-alt2"></span>
						<?php esc_html_e( 'Volver a Empresas', 'reforestamos-empresas' ); ?>
					</a>
				</div>

			</article>

		<?php endwhile; ?>
	</div>
</div>

<?php
get_footer();
