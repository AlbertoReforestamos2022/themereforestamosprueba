<?php
/**
 * Template Name: Galerías de Empresas
 * Template Post Type: page
 *
 * Displays all company galleries in a filterable grid layout.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

get_header();
?>

<div class="companies-galleries-page">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title"><?php the_title(); ?></h1>
			<?php if ( get_the_content() ) : ?>
				<div class="page-description">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</header>

		<?php
		// Get all companies with galleries
		$companies = Reforestamos_Gallery_Manager::get_companies_with_galleries();
		?>

		<?php if ( ! empty( $companies ) ) : ?>
			
			<!-- Filter Section -->
			<div class="galleries-filter">
				<div class="filter-controls">
					<label for="company-filter"><?php esc_html_e( 'Filtrar por empresa:', 'reforestamos-empresas' ); ?></label>
					<select id="company-filter" class="company-filter-select">
						<option value=""><?php esc_html_e( 'Todas las empresas', 'reforestamos-empresas' ); ?></option>
						<?php foreach ( $companies as $company ) : ?>
							<option value="<?php echo esc_attr( $company['id'] ); ?>">
								<?php echo esc_html( $company['title'] ); ?>
								(<?php echo esc_html( $company['image_count'] ); ?> <?php esc_html_e( 'imágenes', 'reforestamos-empresas' ); ?>)
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="galleries-stats">
					<span class="stat-item">
						<strong><?php echo count( $companies ); ?></strong>
						<?php esc_html_e( 'empresas con galerías', 'reforestamos-empresas' ); ?>
					</span>
					<span class="stat-item">
						<strong><?php echo array_sum( array_column( $companies, 'image_count' ) ); ?></strong>
						<?php esc_html_e( 'imágenes totales', 'reforestamos-empresas' ); ?>
					</span>
				</div>
			</div>

			<!-- Galleries Grid -->
			<div class="galleries-grid">
				<?php foreach ( $companies as $company ) : ?>
					<div class="gallery-section" data-company-id="<?php echo esc_attr( $company['id'] ); ?>">
						<div class="gallery-section-header">
							<?php if ( $company['logo'] ) : ?>
								<img src="<?php echo esc_url( $company['logo'] ); ?>" 
									 alt="<?php echo esc_attr( $company['title'] ); ?>"
									 class="company-logo-small"
									 loading="lazy">
							<?php endif; ?>
							<h2 class="company-name">
								<a href="<?php echo esc_url( $company['permalink'] ); ?>">
									<?php echo esc_html( $company['title'] ); ?>
								</a>
							</h2>
							<span class="image-count">
								<?php
								printf(
									/* translators: %d: number of images */
									esc_html( _n( '%d imagen', '%d imágenes', $company['image_count'], 'reforestamos-empresas' ) ),
									$company['image_count']
								);
								?>
							</span>
						</div>

						<div class="gallery-preview">
							<?php
							// Show first 6 images as preview
							$preview_images = array_slice( $company['gallery_data'], 0, 6 );
							foreach ( $preview_images as $image ) :
								?>
								<div class="gallery-preview-item">
									<a href="<?php echo esc_url( $image['large'] ); ?>" 
									   data-lightbox="gallery-<?php echo esc_attr( $company['id'] ); ?>"
									   data-title="<?php echo esc_attr( $company['title'] . ' - ' . ( $image['caption'] ?: $image['title'] ) ); ?>">
										<img src="<?php echo esc_url( $image['thumb'] ); ?>" 
											 alt="<?php echo esc_attr( $image['alt'] ?: $image['title'] ); ?>"
											 loading="lazy">
										<div class="preview-overlay">
											<span class="dashicons dashicons-search"></span>
										</div>
									</a>
								</div>
							<?php endforeach; ?>

							<?php if ( $company['image_count'] > 6 ) : ?>
								<div class="gallery-preview-more">
									<a href="<?php echo esc_url( $company['permalink'] ); ?>" class="view-all-link">
										<span class="dashicons dashicons-images-alt2"></span>
										<?php
										printf(
											/* translators: %d: number of remaining images */
											esc_html__( 'Ver todas (%d más)', 'reforestamos-empresas' ),
											$company['image_count'] - 6
										);
										?>
									</a>
								</div>
							<?php endif; ?>
						</div>

						<div class="gallery-section-footer">
							<a href="<?php echo esc_url( $company['permalink'] ); ?>" class="btn btn-primary">
								<?php esc_html_e( 'Ver perfil completo', 'reforestamos-empresas' ); ?>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		<?php else : ?>
			
			<div class="no-galleries-found">
				<p><?php esc_html_e( 'Aún no hay empresas con galerías publicadas.', 'reforestamos-empresas' ); ?></p>
			</div>

		<?php endif; ?>
	</div>
</div>

<style>
/* Page Header */
.companies-galleries-page {
	padding: 3rem 0;
}

.page-header {
	text-align: center;
	margin-bottom: 3rem;
	padding-bottom: 2rem;
	border-bottom: 3px solid #2E7D32;
}

.page-title {
	font-size: 2.5rem;
	color: #2E7D32;
	margin-bottom: 1rem;
}

.page-description {
	font-size: 1.1rem;
	color: #666;
	max-width: 800px;
	margin: 0 auto;
}

/* Filter Section */
.galleries-filter {
	background: #f9f9f9;
	padding: 1.5rem;
	border-radius: 8px;
	margin-bottom: 2rem;
	display: flex;
	justify-content: space-between;
	align-items: center;
	flex-wrap: wrap;
	gap: 1rem;
}

.filter-controls {
	display: flex;
	align-items: center;
	gap: 1rem;
}

.filter-controls label {
	font-weight: 600;
	color: #333;
}

.company-filter-select {
	padding: 0.5rem 1rem;
	border: 1px solid #ddd;
	border-radius: 4px;
	font-size: 1rem;
	min-width: 250px;
}

.galleries-stats {
	display: flex;
	gap: 2rem;
}

.stat-item {
	font-size: 0.9rem;
	color: #666;
}

.stat-item strong {
	color: #2E7D32;
	font-size: 1.2rem;
	margin-right: 0.25rem;
}

/* Galleries Grid */
.galleries-grid {
	display: grid;
	gap: 2rem;
}

.gallery-section {
	background: #fff;
	border-radius: 8px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
	overflow: hidden;
	transition: box-shadow 0.3s ease;
}

.gallery-section:hover {
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.gallery-section.hidden {
	display: none;
}

/* Gallery Section Header */
.gallery-section-header {
	display: flex;
	align-items: center;
	gap: 1rem;
	padding: 1.5rem;
	background: linear-gradient(135deg, #2E7D32 0%, #66BB6A 100%);
	color: #fff;
}

.company-logo-small {
	width: 60px;
	height: 60px;
	object-fit: contain;
	background: #fff;
	padding: 0.5rem;
	border-radius: 8px;
}

.company-name {
	flex: 1;
	margin: 0;
	font-size: 1.5rem;
}

.company-name a {
	color: #fff;
	text-decoration: none;
	transition: opacity 0.3s ease;
}

.company-name a:hover {
	opacity: 0.8;
}

.image-count {
	background: rgba(255, 255, 255, 0.2);
	padding: 0.5rem 1rem;
	border-radius: 20px;
	font-size: 0.9rem;
}

/* Gallery Preview */
.gallery-preview {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
	gap: 1px;
	background: #f0f0f0;
}

.gallery-preview-item {
	position: relative;
	padding-bottom: 75%;
	overflow: hidden;
	background: #f9f9f9;
}

.gallery-preview-item img {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.3s ease;
}

.gallery-preview-item:hover img {
	transform: scale(1.1);
}

.preview-overlay {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(46, 125, 50, 0.8);
	display: flex;
	align-items: center;
	justify-content: center;
	opacity: 0;
	transition: opacity 0.3s ease;
}

.gallery-preview-item:hover .preview-overlay {
	opacity: 1;
}

.preview-overlay .dashicons {
	color: #fff;
	font-size: 2rem;
	width: 2rem;
	height: 2rem;
}

.gallery-preview-more {
	position: relative;
	padding-bottom: 75%;
	background: #2E7D32;
	display: flex;
	align-items: center;
	justify-content: center;
}

.view-all-link {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	color: #fff;
	text-decoration: none;
	text-align: center;
	font-size: 1.1rem;
	font-weight: 600;
	transition: opacity 0.3s ease;
}

.view-all-link:hover {
	opacity: 0.8;
}

.view-all-link .dashicons {
	display: block;
	font-size: 3rem;
	width: 3rem;
	height: 3rem;
	margin: 0 auto 0.5rem;
}

/* Gallery Section Footer */
.gallery-section-footer {
	padding: 1.5rem;
	text-align: center;
	background: #f9f9f9;
}

.btn {
	display: inline-block;
	padding: 0.75rem 2rem;
	background: #2E7D32;
	color: #fff;
	text-decoration: none;
	border-radius: 4px;
	font-weight: 600;
	transition: background 0.3s ease;
}

.btn:hover {
	background: #1B5E20;
	color: #fff;
}

/* No Galleries Found */
.no-galleries-found {
	text-align: center;
	padding: 3rem;
	background: #f9f9f9;
	border-radius: 8px;
}

.no-galleries-found p {
	font-size: 1.1rem;
	color: #666;
	margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
	.page-title {
		font-size: 2rem;
	}

	.galleries-filter {
		flex-direction: column;
		align-items: stretch;
	}

	.filter-controls {
		flex-direction: column;
		align-items: stretch;
	}

	.company-filter-select {
		width: 100%;
	}

	.galleries-stats {
		justify-content: space-around;
	}

	.gallery-section-header {
		flex-wrap: wrap;
	}

	.company-name {
		font-size: 1.25rem;
	}

	.gallery-preview {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media (max-width: 480px) {
	.companies-galleries-page {
		padding: 1.5rem 0;
	}

	.page-header {
		margin-bottom: 2rem;
	}

	.gallery-preview {
		grid-template-columns: 1fr;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	// Filter galleries by company
	$('#company-filter').on('change', function() {
		var selectedCompany = $(this).val();
		
		if (selectedCompany === '') {
			// Show all galleries
			$('.gallery-section').removeClass('hidden').fadeIn();
		} else {
			// Hide all galleries
			$('.gallery-section').addClass('hidden').hide();
			
			// Show selected company gallery
			$('.gallery-section[data-company-id="' + selectedCompany + '"]')
				.removeClass('hidden')
				.fadeIn();
		}
	});
});
</script>

<?php
get_footer();

