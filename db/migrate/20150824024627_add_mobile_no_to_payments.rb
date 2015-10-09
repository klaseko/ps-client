class AddMobileNoToPayments < ActiveRecord::Migration
  def change
    add_column :payments, :mobile_no, :string
  end
end
