class CreatePayments < ActiveRecord::Migration
  def change
    create_table :payments do |t|
      t.decimal :total
      t.string :description
      t.string :currency
      t.json :urls
      t.integer :client_key
      t.string :ref_no
      t.string :title
      t.json :items
      t.string :email
      t.json :info

      t.timestamps null: false
    end
  end
end
