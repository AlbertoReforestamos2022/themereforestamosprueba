import { useBlockProps } from '@wordpress/block-editor';

/**
 *
 * @param root0
 * @param root0.attributes
 */
export default function Save({ attributes }) {
	const { count, showPast, layout } = attributes;

	const blockProps = useBlockProps.save({
		className: `eventos-proximos-block layout-${layout}`,
		'data-count': count,
		'data-show-past': showPast,
		'data-layout': layout,
	});

	return (
		<div {...blockProps}>
			<div className="eventos-proximos-header">
				<h2>Próximos Eventos</h2>
			</div>
			<div className="eventos-container" data-eventos-container>
				<div className="eventos-loading">
					<div className="spinner-border text-primary" role="status">
						<span className="visually-hidden">
							Cargando eventos...
						</span>
					</div>
					<p>Cargando eventos...</p>
				</div>
			</div>
		</div>
	);
}
