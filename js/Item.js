// js/Menu/Item.js

define(function (require, exports, module) {
  
  var
    Backbone                      = require('backbone'),
    Config                        = require('./Config'),
    Events                        = require('./Events'),
    _                             = require('underscore')
  ;

  var
    direction = Config.direction
  ;

  module.exports = Backbone.View.extend({

    events : {
      'click .m-ia--next' : 'nextSlide',
      'click .m-ia--prev' : 'prevSlide'
    },

    initialize : function () {
      this._id = parseInt(this.$el.attr('data-page-id'), 10);
      this._parentId = parseInt(this.$el.parent().attr('data-page-id'), 10);
    },
    
    nextSlide : function (ev) {
      ev.preventDefault();
      Events.trigger('menu:slide:out', this._parentId, direction.left);
      Events.trigger('menu:slide:in', this._id, direction.right);
    },

    prevSlide : function (ev) {
      ev.preventDefault();
      Events.trigger('menu:slide:in', this._id, direction.left);
      Events.trigger('menu:slide:out', this._parentId, direction.right);
    }

  });

});