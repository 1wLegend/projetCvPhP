module.exports = {
    content: [
        "./app/**/*.php",
        "./src/**/*.php",
    ],
    daisyui: {
        themes: ["light", "dark", "cupcake"],
    },

    plugins: [
        require('daisyui'),
    ],
}