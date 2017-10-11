const CleanWebpackPlugin = require('clean-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin'); //installed via npm
const NameAllModulesPlugin = require('name-all-modules-plugin');
const WebpackBuildNotifierPlugin = require('webpack-build-notifier');
const webpack = require('webpack'); //to access built-in plugins
const path = require('path');

const inProduction = process.env.NODE_ENV === 'production';

const devTool = inProduction ? 'source-map' : 'cheap-eval-source-map';

// CleanWebpackPlugin: the path(s) that should be cleaned
let pathsToClean = [
  'js/main.*.js*',
  'js/vendor.*.js*',
  'js/runtime.*.js*'
];

// the clean options to use
let cleanOptions = {
  root       : __dirname,
  // exclude : ['shared.js'],
  verbose    : true,
  dry        : false,
  watch      : true
};

const config = {
  entry: {
    main: './build/_js/_custom/main.js',
    vendor: [
      'jquery',
      'algoliasearch',
      'autocomplete.js',
      'mailcheck',
      'magnific-popup',
      'select2',
      'cropper',
      'babel-polyfill'
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
    new webpack.NamedModulesPlugin(),
    new webpack.NamedChunksPlugin((chunk) => {
        if (chunk.name) {
            return chunk.name;
        }
        return chunk.modules.map(m => path.relative(m.context, m.request)).join("_");
    }),
    new webpack.optimize.CommonsChunkPlugin({
        name: 'vendor',
        minChunks: Infinity
    }),
    new webpack.optimize.CommonsChunkPlugin({
        name: 'runtime'
    }),
    new NameAllModulesPlugin(),
    // Shim to allow use of legacy plugins (e.g., jquery.validation) requiring jQuery in global space
    new webpack.ProvidePlugin({
        $               : "jquery",
        jQuery          : "jquery",
        "window.jQuery" : "jquery"
    }),
    new WebpackBuildNotifierPlugin({
      title: "GViP Webpack Build",
      logo: path.resolve("./favicon-32x32.png")
    })
  ],
  devtool: devTool,
  module: {
    rules: [
      {
        test: /\.js$/,
        include: path.resolve(__dirname, "build/_js/_custom"),
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['env'],
            cacheDirectory: true
          }
        }
      }
    ]
  }
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