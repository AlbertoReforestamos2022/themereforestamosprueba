/**
 * Contact Block
 *
 * @package
 */

import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import save from './save';
import metadata from './block.json';

registerBlockType(metadata.name, {
	...metadata,
	edit,
	save,
});
