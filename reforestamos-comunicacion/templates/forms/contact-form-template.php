<?php
/**
 * Contact Form Template
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 *
 * @var string $title Form title
 * @var string $button_text Button text
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="reforestamos-contact-form">
	<?php if ( ! empty( $title ) ) : ?>
		<h3 class="contact-form-title"><?php echo esc_html( $title ); ?></h3>
	<?php endif; ?>

	<form class="contact-form" method="post" novalidate>
		<?php wp_nonce_field( 'reforestamos_contact_form', 'contact_form_nonce' ); ?>

		<div class="form-messages"></div>

		<!-- Honeypot field for spam protection (Requirement 9.7) -->
		<div class="form-field-hp" aria-hidden="true">
			<label for="contact-website"><?php esc_html_e( 'Website', 'reforestamos-comunicacion' ); ?></label>
			<input 
				type="text" 
				id="contact-website" 
				name="website" 
				value="" 
				tabindex="-1" 
				autocomplete="off"
			>
		</div>

		<div class="mb-3">
			<label for="contact-nombre" class="form-label">
				<?php esc_html_e( 'Nombre', 'reforestamos-comunicacion' ); ?>
				<span class="required">*</span>
			</label>
			<input 
				type="text" 
				class="form-control" 
				id="contact-nombre" 
				name="nombre" 
				placeholder="<?php esc_attr_e( 'Tu nombre completo', 'reforestamos-comunicacion' ); ?>"
				required
			>
		</div>

		<div class="mb-3">
			<label for="contact-email" class="form-label">
				<?php esc_html_e( 'Email', 'reforestamos-comunicacion' ); ?>
				<span class="required">*</span>
			</label>
			<input 
				type="email" 
				class="form-control" 
				id="contact-email" 
				name="email" 
				placeholder="<?php esc_attr_e( 'tu@email.com', 'reforestamos-comunicacion' ); ?>"
				required
			>
		</div>

		<div class="mb-3">
			<label for="contact-asunto" class="form-label">
				<?php esc_html_e( 'Asunto', 'reforestamos-comunicacion' ); ?>
				<span class="required">*</span>
			</label>
			<input 
				type="text" 
				class="form-control" 
				id="contact-asunto" 
				name="asunto" 
				placeholder="<?php esc_attr_e( 'Asunto de tu mensaje', 'reforestamos-comunicacion' ); ?>"
				required
			>
		</div>

		<div class="mb-3">
			<label for="contact-mensaje" class="form-label">
				<?php esc_html_e( 'Mensaje', 'reforestamos-comunicacion' ); ?>
				<span class="required">*</span>
			</label>
			<textarea 
				class="form-control" 
				id="contact-mensaje" 
				name="mensaje" 
				rows="5" 
				placeholder="<?php esc_attr_e( 'Escribe tu mensaje aquí...', 'reforestamos-comunicacion' ); ?>"
				required
			></textarea>
		</div>

		<div class="form-actions">
			<button 
				type="submit" 
				class="btn btn-primary" 
				data-original-text="<?php echo esc_attr( $button_text ); ?>"
			>
				<?php echo esc_html( $button_text ); ?>
			</button>
		</div>
	</form>
</div>
