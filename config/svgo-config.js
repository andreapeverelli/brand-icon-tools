export default {
	multipass: true,

	floatPrecision: 2,

	js2svg: {
		pretty: false,
		indent: 0,
		finalNewline: true,
	},

	plugins: [
		{
			name: 'preset-default',
			params: {
				overrides: {
					cleanupIds: true,
					removeDesc: true,
					removeUnknownsAndDefaults: true,
					removeUselessStrokeAndFill: true,
					removeHiddenElems: true,
					removeEmptyContainers: true,
					removeEmptyText: true,
					removeMetadata: true,
					removeComments: true,
					removeEditorsNSData: true,
				},
			},
		},
		{
			name: 'convertPathData',
			params: {
				floatPrecision: 2,
				transformPrecision: 2,
				makeArcs: true,
				straightCurves: true,
				lineShorthands: true,
				curveSmoothShorthands: true,
			},
		},
		{
			name: 'cleanupNumericValues',
			params: {
				floatPrecision: 2,
			},
		},
		{
			name: 'convertTransform',
			params: {
				floatPrecision: 2,
			},
		},
		'removeTitle',
		'removeDimensions',
		'convertShapeToPath',
		'convertEllipseToCircle',
		'convertColors',
		'convertStyleToAttrs',
		'inlineStyles',
		'minifyStyles',
		'collapseGroups',
		'moveElemsAttrsToGroup',
		'moveGroupAttrsToElems',
		'mergeStyles',
		'mergePaths',
		'reusePaths',
		'sortAttrs',
		'sortDefsChildren',
		'removeXMLProcInst',
		'removeDoctype',
		'removeXMLNS',
		'removeUnusedNS',
	],
};
