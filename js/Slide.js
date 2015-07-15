// js/Menu/Slide.js

define(function (require, arguments, module) {
  
  var
    Backbone                        = require('backbone'),
    MenuItem                        = require('./Item'),
    Config                          = require('./Config'),
    Events                          = require('./Events'),
    _                               = require('underscore')
  ;

  var
    status = Config.status,
    direction = Config.direction
  ;

  module.exports = Backbone.View.extend({

    initialize : function () {
      
      this._items = [];

      this._id = parseInt(this.$el.attr('data-page-id'), 10);

      _(this.$('.m-i')).each(function (item) {
        this._items.push(new MenuItem({el : item}));
      }, this);

      this.attachEvents();

      this._slideStatus = this.$el.is('.m-l--a') ? status.isIn : status.isOut;
    },

    attachEvents : function () {
      this.listenTo(Events, 'menu:slide:out', this.slideOut);
      this.listenTo(Events, 'menu:slide:in', this.slideIn);
    },

    slideOut : function (id, to) {
      if (this._id !== id) return;
      if (to === direction.left) this.slideOutToLeft();
      else if (to === direction.right) this.slideOutToRight();
      else throw new Error('This should not have happened. Only `left` or `right` directions are accepted.');
    },

    slideIn : function (id, to) {
      if (this._id !== id) return;
      if (to === direction.left) this.slideInFromLeft();
      else if (to === direction.right) this.slideInFromRight();
      else throw new Error('This should not have happened. Only `left` or `right` directions are accepted.');
    },

    slideInFromLeft : function (callback) {
      if (this._slideStatus !== status.isOut) return;
      this._slideStatus = status.progress;
      this.place('left');
      this.$el.addClass('m-l--a');

      function then () {
        if (this._slideStatus !== status.progress) return;
        this._slideStatus = status.isIn;
      }

      this.animate('0%', _.bind(then, this));
    },

    slideInFromRight : function () {
      if (this._slideStatus !== status.isOut) return;
      this._slideStatus = status.progress;
      this.place('right');
      this.$el.addClass('m-l--a');

      function then () {
        if (this._slideStatus !== status.progress) return;
        this._slideStatus = status.isIn;
      }

      this.animate('0%', _.bind(then, this));
    },

    slideOutToRight : function () {
      if (this._slideStatus === status.isOut) return;
      if (this._slideStatus === status.progress) {
        this.$el.stop();
        this.place('right');
        this.$el.removeClass('m-l--a');
        this._slideStatus = status.isOut;
        return;
      }
      this._slideStatus = status.progress;
      this.$el.removeClass('m-l--a');

      function then () {
        if (this._slideStatus !== status.progress) return;
        this._slideStatus = status.isOut;
      }

      this.animate('100%', _.bind(then, this));
    },

    slideOutToLeft : function () {
      if (this._slideStatus === status.isOut) return;
      if (this._slideStatus === status.progress) {
        this.$el.stop();
        this.place('left');
        this.$el.removeClass('m-l--a');
        this._slideStatus = status.isOut;
        return;  
      }
      this._slideStatus = status.progress;
      this.$el.removeClass('m-l--a');

      function then () {
        if (this._slideStatus !== status.progress) return;
        this._slideStatus = status.isOut;
      }

      this.animate('-100%', _.bind(then, this));
    },

    place : function (position) {
      position = (position === 'right') ? '100%' : '-100%';
      this.$el.css('left', position);
    },

    animate : function (left, callback) {
      this.$el.animate({
        'left' : left
      }, Config.menuSlideSpeed, 'easeOutExpo', callback);
    }

  });

});