class ChangeItemsAndInfoColumnsInPayments < ActiveRecord::Migration
  def change
    remove_column :payments, :info
    remove_column :payments, :items
    add_column :payments, :info, :json, array: true, default: []
    add_column :payments, :items, :json, array: true, default: []
  end
end
