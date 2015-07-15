// js/Menu/Menu.js

define(function (require, exports, module) {
  
  var
    Backbone                      = require('backbone'),
    MenuSlide                     = require('./Slide'),
    Config                        = require('./Config'),
    _                             = require('underscore')
  ;

  var
    status = Config.status;
  ;

  module.exports = Backbone.View.extend({

    el : '#m_l__w',

    initialize : function () {
      var slide;
      this._slides = [];

      _(this.$('.m-l')).each(function (item) {
        slide = new MenuSlide( {el : item});
        this._slides.push(slide);
      }, this);
    }

  });

});