<?php
class EnumerationsController extends AppController {
#  before_filter :require_admin
#  
  function index(){
    $this->getlist();
    $this->render('list');
  }
#
#  # GETs should be safe (see http://www.w3.org/2001/tag/doc/whenToUseGet.html)
#  verify :method => :post, :only => [ :destroy, :create, :update ],
#         :redirect_to => { :action => :list }
#
  function getlist(){
    
  }
  
#
#  def new
#    @enumeration = Enumeration.new(:opt => params[:opt])
#  end
#
#  def create
#    @enumeration = Enumeration.new(params[:enumeration])
#    if @enumeration.save
#      flash[:notice] = l(:notice_successful_create)
#      redirect_to :action => 'list', :opt => @enumeration.opt
#    else
#      render :action => 'new'
#    end
#  end
#
#  def edit
#    @enumeration = Enumeration.find(params[:id])
#  end
#
#  def update
#    @enumeration = Enumeration.find(params[:id])
#    if @enumeration.update_attributes(params[:enumeration])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'list', :opt => @enumeration.opt
#    else
#      render :action => 'edit'
#    end
#  end
#
#  def move
#    @enumeration = Enumeration.find(params[:id])
#    case params[:position]
#    when 'highest'
#      @enumeration.move_to_top
#    when 'higher'
#      @enumeration.move_higher
#    when 'lower'
#      @enumeration.move_lower
#    when 'lowest'
#      @enumeration.move_to_bottom
#    end if params[:position]
#    redirect_to :action => 'index'
#  end
#  
#  def destroy
#    @enumeration = Enumeration.find(params[:id])
#    if !@enumeration.in_use?
#      # No associated objects
#      @enumeration.destroy
#      redirect_to :action => 'index'
#    elsif params[:reassign_to_id]
#      if reassign_to = Enumeration.find_by_opt_and_id(@enumeration.opt, params[:reassign_to_id])
#        @enumeration.destroy(reassign_to)
#        redirect_to :action => 'index'
#      end
#    end
#    @enumerations = Enumeration.get_values(@enumeration.opt) - [@enumeration]
#  #rescue
#  #  flash[:error] = 'Unable to delete enumeration'
#  #  redirect_to :action => 'index'
#  end
#end
}