var express = require("express");
var cors = require("cors");
var app = express();

var books = require("./books");

app.use(cors({ origin: "http://localhost:8080" }));

app.get("/", (req, res) => {
  res.sendFile("./assets/sample.pdf", { root: __dirname });
});

app.get("/search", (req, res) => {
  res.json(books);
});

app.listen("8088");
