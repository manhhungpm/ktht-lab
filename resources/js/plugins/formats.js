import Vue from "vue";
import numeral from "numeral";

Vue.filter("number", function(numberStr) {
    return numeral(String(numberStr)).format("0,0.[00]");
});
