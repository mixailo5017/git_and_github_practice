const CleanWebpackPlugin = require('clean-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin'); //installed via npm
const webpack = require('webpack'); //to access built-in plugins
const path = require('path');

const inProduction = process.env.NODE_ENV === 'production';

const devTool = inProduction ? 'source-map' : 'cheap-eval-source-map';

// CleanWebpackPlugin: the path(s) that should be cleaned
let pathsToClean = [
  'js/main.*.js',
  'js/vendor.*.js'
];

// the clean options to use
let cleanOptions = {
  root:     __dirname,
  // exclude:  ['shared.js'],
  verbose:  true,
  dry:      false
};

const config = {
  entry: {
    main: './build/_js/_custom/main.js',
    vendor: [
      'jquery',
      'algoliasearch',
      'autocomplete.js'
    ]
  },
  output: {
    path: path.resolve(__dirname, 'js'),
    filename: '[name].[chunkhash].js'
  },
  plugins: [
    new CleanWebpackPlugin(pathsToClean, cleanOptions),
    new HtmlWebpackPlugin({
      filename: 'bundles.html',
      template: './build/_js/includes.ejs',
      inject: false
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor'
    }),
  ],
  devtool: devTool
};

// Minify JS in production
if (inProduction) {
  config.plugins.push(
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true
    }
  ));
}

module.exports = config;