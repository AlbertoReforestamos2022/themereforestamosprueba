import { useBlockProps } from '@wordpress/block-editor';

/**
 *
 * @param root0
 * @param root0.attributes
 * @param root0.clientId
 */
export default function Save({ attributes, clientId }) {
	const { faqs, openFirst, allowMultiple, accordionStyle } = attributes;

	const blockProps = useBlockProps.save({
		className: `reforestamos-faqs reforestamos-faqs--${accordionStyle}`,
	});

	if (!faqs || faqs.length === 0) {
		return null;
	}

	// Generate a unique ID for the accordion
	const accordionId = `accordion-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

	return (
		<div {...blockProps}>
			<div
				className={`accordion accordion-${accordionStyle}`}
				id={accordionId}
			>
				{faqs.map((faq, index) => {
					const itemId = `faq-${accordionId}-${index}`;
					const isOpen = openFirst && index === 0;

					return (
						<div key={index} className="accordion-item">
							<h2
								className="accordion-header"
								id={`heading-${itemId}`}
							>
								<button
									className={`accordion-button ${!isOpen ? 'collapsed' : ''}`}
									type="button"
									data-bs-toggle="collapse"
									data-bs-target={`#collapse-${itemId}`}
									aria-expanded={isOpen}
									aria-controls={`collapse-${itemId}`}
								>
									{faq.question}
								</button>
							</h2>
							<div
								id={`collapse-${itemId}`}
								className={`accordion-collapse collapse ${isOpen ? 'show' : ''}`}
								aria-labelledby={`heading-${itemId}`}
								data-bs-parent={
									allowMultiple ? '' : `#${accordionId}`
								}
							>
								<div className="accordion-body">
									{faq.answer}
								</div>
							</div>
						</div>
					);
				})}
			</div>
		</div>
	);
}
