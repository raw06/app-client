module.exports = {
    root: true,
    env: { browser: true, es2020: true },
    extends: [
      'eslint:recommended',
      'plugin:react/recommended',
      'plugin:react/jsx-runtime',
      'plugin:react-hooks/recommended',
    ],
    ignorePatterns: [
          'dist',
          '.eslintrc.cjs',
          'public',
          'webpack.mix.js',
          'webpack.mix-css.js',
          'webpack.fe-css.js',
          'resource/js/setupTests.jsx'
      ],
    parserOptions: { ecmaVersion: 'latest', sourceType: 'module' },
    settings: { react: { version: '18.2' } },
    plugins: ['react-refresh'],
    rules: {
      'react-refresh/only-export-components': [
        'warn',
        { allowConstantExport: true },
      ]
    },
  }
  