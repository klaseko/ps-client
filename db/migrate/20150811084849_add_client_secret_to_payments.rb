class AddClientSecretToPayments < ActiveRecord::Migration
  def change
    add_column :payments, :client_secret, :string
  end
end
