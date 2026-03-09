import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './style.scss';

registerBlockType('reforestamos/cards-enlaces', {
    edit: Edit,
    save: Save,
});
