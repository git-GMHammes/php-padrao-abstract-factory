<!-- Exemplos de uso: O padrão Abstract Factory é bastante comum no código PHP. Muitas frameworks e bibliotecas 
o utilizam para fornecer uma maneira de estender e personalizar seus componentes padrão.
Identificação: O padrão é fácil de reconhecer pelos seus métodos, que retornam um objeto fárica. Em seguida, 
a fábrica é usado para criar subcomponentes específicos. -->

<?php

namespace RefactoringGuru\AbstractFactory\Conceptual;

// A interface Abstract Factory declara um conjunto de métodos que retornam
// diferentes produtos abstratos. Esses produtos são chamados de família e são
// relacionados por um tema ou conceito de alto nível. Os produtos de uma família geralmente são
// capazes de colaborar entre si. Uma família de produtos pode ter vários
// variantes, mas os produtos de uma variante são incompatíveis com produtos de
// outro.
interface AbstractFactory
{
    public function createProductA(): AbstractProductA;

    public function createProductB(): AbstractProductB;
}

// As "Fábricas de Concreto" produzem uma família de produtos que pertencem a um único
// variante. A fábrica garante que os produtos resultantes são compatíveis. Observação
// que as assinaturas dos métodos da Concrete Factory retornam um produto abstrato,
// enquanto dentro do método um produto concreto é instanciado.

class ConcreteFactory1 implements AbstractFactory
{
    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA1();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB1();
    }
}

/**
 * Cada "Fábrica de Concreto" tem uma variante de produto correspondente.
 */
class ConcreteFactory2 implements AbstractFactory
{
    public function createProductA(): AbstractProductA
    {
        return new ConcreteProductA2();
    }

    public function createProductB(): AbstractProductB
    {
        return new ConcreteProductB2();
    }
}

// Cada produto distinto de uma família de produtos deve ter uma interface básica. Todo
// variantes do produto devem implementar esta interface.

interface AbstractProductA
{
    public function usefulFunctionA(): string;
}

/**
 * Cada produto distinto de uma família de produtos deve ter uma interface básica. Todo
 * variantes do produto devem implementar esta interface.
 */
class ConcreteProductA1 implements AbstractProductA
{
    public function usefulFunctionA(): string
    {
        return "The result of the product A1.";
    }
}

class ConcreteProductA2 implements AbstractProductA
{
    public function usefulFunctionA(): string
    {
        return "The result of the product A2.";
    }
}

/**
 * Aqui está a interface base de outro produto. Todos os produtos podem interagir
 * entre si, mas a interação adequada só é possível entre produtos de
 * a mesma variante concreta.
 */
interface AbstractProductB
{
    /**
     * O produto B é capaz de fazer suas próprias coisas...
     */
    public function usefulFunctionB(): string;

    /**
     * ...mas também pode colaborar com o Product A.
     *
     * A Abstract Factory garante que todos os produtos que cria são da
     * mesma variante e, portanto, compatível.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string;
}

/**
 * Os Produtos de Concreto são criados pelas Fábricas de Concreto correspondentes.
 */
class ConcreteProductB1 implements AbstractProductB
{
    public function usefulFunctionB(): string
    {
        return "The result of the product B1.";
    }

    /**
     * A variante, Produto B1, só pode funcionar corretamente com a variante,
     * Produto A1. No entanto, aceita qualquer instância de AbstractProductA como
     * um argumento.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "The result of the B1 collaborating with the ({$result})";
    }
}

class ConcreteProductB2 implements AbstractProductB
{
    public function usefulFunctionB(): string
    {
        return "The result of the product B2.";
    }

    /**
     * A variante, Produto B2, só funciona corretamente com a variante,
     * Produto A2. No entanto, aceita qualquer instância de AbstractProductA como
     * um argumento.
     */
    public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
    {
        $result = $collaborator->usefulFunctionA();

        return "The result of the B2 collaborating with the ({$result})";
    }
}

/**
 * O código do cliente funciona com fábricas e produtos apenas por meio de resumo
 * tipos: AbstractFactory e AbstractProduct. Isso permite que você passe qualquer fábrica ou
 * subclasse do produto para o código do cliente sem quebrá-lo.
 */
function clientCode(AbstractFactory $factory)
{
    $productA = $factory->createProductA();
    $productB = $factory->createProductB();

    echo $productB->usefulFunctionB() . "\n";
    echo $productB->anotherUsefulFunctionB($productA) . "\n";
}

/**
 * O código do cliente pode funcionar com qualquer classe de fábrica concreta.
 */
echo "Client: Testing client code with the first factory type:\n";
clientCode(new ConcreteFactory1());

echo "\n";

echo "Client: Testing the same client code with the second factory type:\n";
clientCode(new ConcreteFactory2());
