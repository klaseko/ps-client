class AddTrackingIdToPayments < ActiveRecord::Migration
  def change
    add_column :payments, :tracking_id, :string
  end
end
